<?php

declare(strict_types=1);

namespace Tests\Feature\Integration;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\Certificate;
use App\Models\UserGamification;
use App\Models\UserProgress;
use App\Models\Notification;
use App\Models\PointsHistory;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Tests\TestCase;

class DataConsistencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste de consistência de dados entre GamificationService e UserGamification
     */
    public function test_gamification_data_consistency(): void
    {
        // Arrange
        $user = User::factory()->create();
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $gamificationService = new GamificationService($notificationService, $dispatcher);

        // Act - Adicionar pontos em sequência
        $pointsSequence = [50, 75, 100, 25, 150];
        $expectedTotal = 0;

        foreach ($pointsSequence as $points) {
            $gamificationService->addPoints($user, $points, "Test points: {$points}");
            $expectedTotal += $points;
        }

        // Assert
        $user->refresh();
        $gamification = $user->gamification;

        // Verificar total de pontos
        $this->assertEquals($expectedTotal, $gamification->total_points);

        // Verificar histórico de pontos
        $pointsHistory = PointsHistory::where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $this->assertCount(count($pointsSequence), $pointsHistory);

        // Verificar que cada entrada no histórico está correta
        $runningTotal = 0;
        foreach ($pointsHistory as $index => $entry) {
            $runningTotal += $entry->points;
            $this->assertEquals($pointsSequence[$index], $entry->points);
            $this->assertEquals($runningTotal, $entry->new_total);
            $this->assertEquals($runningTotal - $entry->points, $entry->old_total);
        }

        // Verificar nível baseado nos pontos
        $this->assertLevelConsistency($gamification->total_points, $gamification->current_level);
    }

    /**
     * Teste de consistência de dados entre CertificateService e Certificate
     */
    public function test_certificate_data_consistency(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create([
            'title' => 'Módulo de Teste',
            'category' => 'hr',
            'points_reward' => 100
        ]);

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => $user->id,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $pdfService = $this->createMock(\App\Services\PDFGenerationService::class);
        $pdfService->method('generatePDF')
            ->willReturn(true);

        $certificateService = new CertificateService($notificationService, $pdfService);

        // Act
        $certificate = $certificateService->generateModuleCertificate($user, $module);

        // Assert
        $this->assertCertificateDataConsistency($certificate, $user, $module);

        // Verificar que o certificado pode ser verificado
        $verifiedCertificate = $certificateService->verifyCertificate($certificate->verification_code);
        $this->assertNotNull($verifiedCertificate);
        $this->assertEquals($certificate->id, $verifiedCertificate->id);

        // Verificar que não há duplicatas
        $duplicateCertificates = Certificate::where('user_id', $user->id)
            ->where('type', 'module')
            ->where('reference_id', $module->id)
            ->get();

        $this->assertCount(1, $duplicateCertificates);
    }

    /**
     * Teste de consistência de dados entre UserProgress e Module completion
     */
    public function test_user_progress_data_consistency(): void
    {
        // Arrange
        $user = User::factory()->create();
        $modules = Module::factory()->count(3)->create([
            'points_reward' => 100
        ]);

        // Act - Simular progresso em múltiplos módulos
        $progressData = [
            ['module_id' => $modules[0]->id, 'progress' => 100, 'status' => 'completed'],
            ['module_id' => $modules[1]->id, 'progress' => 50, 'status' => 'in_progress'],
            ['module_id' => $modules[2]->id, 'progress' => 0, 'status' => 'not_started']
        ];

        foreach ($progressData as $data) {
            UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'module_id' => $data['module_id']
                ],
                [
                    'progress_percentage' => $data['progress'],
                    'status' => $data['status'],
                    'completed_at' => $data['status'] === 'completed' ? now() : null
                ]
            );
        }

        // Assert
        $userProgress = UserProgress::where('user_id', $user->id)->get();

        $this->assertCount(3, $userProgress);

        // Verificar consistência de status e progresso
        foreach ($userProgress as $progress) {
            $expectedData = collect($progressData)
                ->firstWhere('module_id', $progress->module_id);

            $this->assertEquals($expectedData['progress'], $progress->progress_percentage);
            $this->assertEquals($expectedData['status'], $progress->status);

            if ($expectedData['status'] === 'completed') {
                $this->assertNotNull($progress->completed_at);
            } else {
                $this->assertNull($progress->completed_at);
            }
        }

        // Verificar cálculo de progresso geral
        $completedModules = $userProgress->where('status', 'completed')->count();
        $totalModules = $userProgress->count();
        $overallProgress = ($completedModules / $totalModules) * 100;

        $this->assertEquals(33.33, round($overallProgress, 2));
    }

    /**
     * Teste de consistência de dados entre QuizAttempt e Quiz results
     */
    public function test_quiz_attempt_data_consistency(): void
    {
        // Arrange
        $user = User::factory()->create();
        $quiz = Quiz::factory()->create([
            'title' => 'Quiz de Teste',
            'passing_score' => 70
        ]);

        // Criar questões
        $questions = [];
        for ($i = 1; $i <= 5; $i++) {
            $questions[] = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => "Questão {$i}?",
                'type' => 'multiple_choice',
                'options' => ['A' => 'Opção A', 'B' => 'Opção B', 'C' => 'Opção C'],
                'correct_answer' => 'A',
                'points' => 20,
                'order_index' => $i
            ]);
        }

        // Act - Criar tentativa de quiz
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
            'completed_at' => now()->addMinutes(15)
        ]);

        // Simular respostas (3 corretas, 2 incorretas = 60%)
        $answers = [
            1 => 'A', // Correta
            2 => 'B', // Incorreta
            3 => 'A', // Correta
            4 => 'C', // Incorreta
            5 => 'A'  // Correta
        ];

        foreach ($answers as $questionId => $answer) {
            $question = $questions[$questionId - 1];
            $isCorrect = $answer === $question->correct_answer;
            
            \App\Models\QuizAttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $questionId,
                'selected_answer' => $answer,
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $question->points : 0
            ]);
        }

        // Calcular score
        $correctAnswers = collect($answers)->filter(function ($answer, $questionId) use ($questions) {
            return $answer === $questions[$questionId - 1]->correct_answer;
        })->count();

        $totalQuestions = count($questions);
        $score = ($correctAnswers / $totalQuestions) * 100;
        $passed = $score >= $quiz->passing_score;

        // Atualizar tentativa
        $attempt->update([
            'score' => $score,
            'passed' => $passed,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers
        ]);

        // Assert
        $attempt->refresh();

        $this->assertEquals(60, $attempt->score);
        $this->assertFalse($attempt->passed); // 60% < 70%
        $this->assertEquals(5, $attempt->total_questions);
        $this->assertEquals(3, $attempt->correct_answers);

        // Verificar respostas individuais
        $attemptAnswers = $attempt->answers;
        $this->assertCount(5, $attemptAnswers);

        $correctAttemptAnswers = $attemptAnswers->where('is_correct', true);
        $this->assertCount(3, $correctAttemptAnswers);

        // Verificar pontos ganhos
        $totalPointsEarned = $attemptAnswers->sum('points_earned');
        $expectedPoints = 3 * 20; // 3 corretas * 20 pontos cada
        $this->assertEquals($expectedPoints, $totalPointsEarned);
    }

    /**
     * Teste de consistência de dados entre Notification e User notifications
     */
    public function test_notification_data_consistency(): void
    {
        // Arrange
        $user = User::factory()->create();
        $dispatcher = $this->createMock(Dispatcher::class);
        $mailer = $this->createMock(Mailer::class);

        $notificationService = new NotificationService($dispatcher, $mailer);

        // Act - Criar múltiplas notificações
        $notifications = [
            [
                'title' => 'Bem-vindo!',
                'message' => 'Bem-vindo ao sistema',
                'type' => 'info',
                'icon' => 'user',
                'color' => 'blue'
            ],
            [
                'title' => 'Módulo Completo',
                'message' => 'Você completou um módulo',
                'type' => 'success',
                'icon' => 'check',
                'color' => 'green'
            ],
            [
                'title' => 'Certificado Gerado',
                'message' => 'Seu certificado foi gerado',
                'type' => 'success',
                'icon' => 'academic-cap',
                'color' => 'yellow'
            ]
        ];

        foreach ($notifications as $notificationData) {
            $notificationService->sendToUser(
                $user,
                $notificationData['title'],
                $notificationData['message'],
                $notificationData['type'],
                $notificationData['icon'],
                $notificationData['color']
            );
        }

        // Assert
        $userNotifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $this->assertCount(3, $userNotifications);

        // Verificar consistência de dados
        foreach ($userNotifications as $index => $notification) {
            $expectedData = $notifications[$index];

            $this->assertEquals($expectedData['title'], $notification->title);
            $this->assertEquals($expectedData['message'], $notification->message);
            $this->assertEquals($expectedData['type'], $notification->type);
            $this->assertEquals($expectedData['icon'], $notification->icon);
            $this->assertEquals($expectedData['color'], $notification->color);
            $this->assertNull($notification->read_at); // Não lida inicialmente
        }

        // Marcar como lida
        $firstNotification = $userNotifications->first();
        $firstNotification->update(['read_at' => now()]);

        // Verificar contagem de não lidas
        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(2, $unreadCount);
    }

    /**
     * Teste de consistência de dados entre ActivityTracking e User activities
     */
    public function test_activity_tracking_data_consistency(): void
    {
        // Arrange
        $user = User::factory()->create();
        $activityTrackingService = new ActivityTrackingService();

        // Act - Rastrear múltiplas atividades
        $activities = [
            [
                'action' => 'login',
                'data' => ['ip' => '192.168.1.1', 'user_agent' => 'Mozilla/5.0']
            ],
            [
                'action' => 'module_view',
                'data' => ['module_id' => 1, 'module_title' => 'Test Module']
            ],
            [
                'action' => 'quiz_start',
                'data' => ['quiz_id' => 1, 'quiz_title' => 'Test Quiz']
            ],
            [
                'action' => 'certificate_earned',
                'data' => ['certificate_id' => 1, 'certificate_type' => 'module']
            ]
        ];

        foreach ($activities as $activity) {
            $activityTrackingService->trackActivity($user, $activity['action'], $activity['data']);
        }

        // Assert
        $stats = $activityTrackingService->getUserActivityStats($user);

        $this->assertEquals(4, $stats['total_activities']);
        $this->assertNotNull($stats['last_activity']);
        $this->assertEquals('login', $stats['most_common_action']);

        // Verificar atividades individuais
        $userActivities = $activityTrackingService->getUserActivities($user);

        $this->assertCount(4, $userActivities);

        // Verificar que cada atividade tem os dados corretos
        foreach ($userActivities as $index => $activity) {
            $expectedActivity = $activities[$index];

            $this->assertEquals($expectedActivity['action'], $activity->action);
            $this->assertEquals($expectedActivity['data'], $activity->data);
            $this->assertEquals($user->id, $activity->user_id);
            $this->assertNotNull($activity->created_at);
        }

        // Verificar atividades por ação
        $loginActivities = $activityTrackingService->getUserActivities($user, 'login');
        $this->assertCount(1, $loginActivities);

        $moduleActivities = $activityTrackingService->getUserActivities($user, 'module_view');
        $this->assertCount(1, $moduleActivities);
    }

    /**
     * Helper para verificar consistência de nível
     */
    private function assertLevelConsistency(int $points, string $level): void
    {
        $expectedLevel = match (true) {
            $points >= 0 && $points < 100 => 'Rookie',
            $points >= 100 && $points < 500 => 'Beginner',
            $points >= 500 && $points < 1000 => 'Explorer',
            $points >= 1000 && $points < 2000 => 'Intermediate',
            $points >= 2000 && $points < 5000 => 'Advanced',
            $points >= 5000 && $points < 10000 => 'Expert',
            $points >= 10000 => 'Master',
            default => 'Rookie'
        };

        $this->assertEquals($expectedLevel, $level);
    }

    /**
     * Helper para verificar consistência de certificado
     */
    private function assertCertificateDataConsistency(Certificate $certificate, User $user, Module $module): void
    {
        $this->assertEquals($user->id, $certificate->user_id);
        $this->assertEquals('module', $certificate->type);
        $this->assertEquals($module->id, $certificate->reference_id);
        $this->assertEquals($module->category, $certificate->category);
        $this->assertNotEmpty($certificate->verification_code);
        $this->assertNotNull($certificate->issued_at);
        $this->assertStringContainsString($module->title, $certificate->title);
        $this->assertIsArray($certificate->metadata);
        $this->assertArrayHasKey('module_title', $certificate->metadata);
        $this->assertArrayHasKey('completion_date', $certificate->metadata);
        $this->assertArrayHasKey('points_earned', $certificate->metadata);
    }
} 