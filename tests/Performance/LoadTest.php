<?php

declare(strict_types=1);

namespace Tests\Performance;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\Certificate;
use App\Models\UserGamification;
use App\Models\Notification;
use App\Services\GamificationService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use App\Services\ActivityTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Mail\Mailer;
use Tests\TestCase;

class LoadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste de carga para múltiplos usuários simultâneos
     */
    public function test_concurrent_users_load(): void
    {
        // Arrange
        $userCount = 100;
        $users = User::factory()->count($userCount)->create();
        $modules = Module::factory()->count(5)->create([
            'points_reward' => 100
        ]);

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular múltiplos usuários acessando simultaneamente
        $results = [];
        
        foreach ($users as $user) {
            $this->actingAs($user);
            
            // Simular acesso ao dashboard
            $dashboardStart = microtime(true);
            $response = $this->get('/dashboard');
            $dashboardTime = microtime(true) - $dashboardStart;
            
            $results[] = [
                'user_id' => $user->id,
                'dashboard_time' => $dashboardTime,
                'status' => $response->status()
            ];
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        // Verificar performance
        $this->assertLessThan(30.0, $totalTime, "100 usuários devem ser processados em menos de 30 segundos");
        $this->assertLessThan(100 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 100MB");

        // Verificar que todos os usuários foram processados
        $this->assertCount($userCount, $results);

        // Verificar tempo médio por usuário
        $avgTime = array_sum(array_column($results, 'dashboard_time')) / count($results);
        $this->assertLessThan(0.5, $avgTime, "Tempo médio por usuário deve ser menor que 0.5 segundos");

        // Verificar que todos os requests foram bem-sucedidos
        $successCount = count(array_filter($results, fn($r) => $r['status'] === 200));
        $this->assertEquals($userCount, $successCount, "Todos os requests devem ter status 200");
    }

    /**
     * Teste de carga para operações de banco de dados
     */
    public function test_database_operations_load(): void
    {
        // Arrange
        $operationCount = 1000;
        $users = User::factory()->count(100)->create();
        $modules = Module::factory()->count(10)->create();

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular múltiplas operações de banco
        $results = [];
        
        for ($i = 0; $i < $operationCount; $i++) {
            $user = $users->random();
            $module = $modules->random();
            
            $operationStart = microtime(true);
            
            // Simular operação de progresso
            \App\Models\UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'module_id' => $module->id
                ],
                [
                    'progress_percentage' => rand(0, 100),
                    'status' => ['not_started', 'in_progress', 'completed'][rand(0, 2)]
                ]
            );
            
            $operationTime = microtime(true) - $operationStart;
            $results[] = $operationTime;
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        $this->assertLessThan(60.0, $totalTime, "1000 operações devem ser completadas em menos de 60 segundos");
        $this->assertLessThan(200 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 200MB");

        // Verificar tempo médio por operação
        $avgTime = array_sum($results) / count($results);
        $this->assertLessThan(0.1, $avgTime, "Tempo médio por operação deve ser menor que 0.1 segundos");
    }

    /**
     * Teste de carga para serviços de gamificação
     */
    public function test_gamification_service_load(): void
    {
        // Arrange
        $userCount = 50;
        $users = User::factory()->count($userCount)->create();
        
        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => 1,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $dispatcher = $this->createMock(Dispatcher::class);
        $gamificationService = new GamificationService($notificationService, $dispatcher);

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular múltiplas operações de gamificação
        $results = [];
        
        foreach ($users as $user) {
            $operationStart = microtime(true);
            
            // Adicionar pontos
            $gamificationService->addPoints($user, rand(10, 100), 'Load test');
            
            $operationTime = microtime(true) - $operationStart;
            $results[] = $operationTime;
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        $this->assertLessThan(10.0, $totalTime, "50 operações de gamificação devem ser completadas em menos de 10 segundos");
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 50MB");

        // Verificar que pontos foram adicionados
        $totalPoints = UserGamification::sum('total_points');
        $this->assertGreaterThan(0, $totalPoints, "Pontos devem ter sido adicionados");
    }

    /**
     * Teste de carga para geração de certificados
     */
    public function test_certificate_generation_load(): void
    {
        // Arrange
        $certificateCount = 100;
        $users = User::factory()->count($certificateCount)->create();
        $modules = Module::factory()->count(10)->create();

        $notificationService = $this->createMock(NotificationService::class);
        $notificationService->method('sendToUser')
            ->willReturn(new Notification([
                'user_id' => 1,
                'title' => 'Test',
                'message' => 'Test message'
            ]));

        $pdfService = $this->createMock(\App\Services\PDFGenerationService::class);
        $pdfService->method('generatePDF')
            ->willReturn(true);

        $certificateService = new CertificateService($notificationService, $pdfService);

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular geração de múltiplos certificados
        $results = [];
        
        foreach ($users as $user) {
            $module = $modules->random();
            
            $operationStart = microtime(true);
            
            // Gerar certificado
            $certificate = $certificateService->generateModuleCertificate($user, $module);
            
            $operationTime = microtime(true) - $operationStart;
            $results[] = [
                'time' => $operationTime,
                'success' => $certificate !== null
            ];
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        $this->assertLessThan(30.0, $totalTime, "100 certificados devem ser gerados em menos de 30 segundos");
        $this->assertLessThan(100 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 100MB");

        // Verificar que certificados foram gerados
        $successCount = count(array_filter($results, fn($r) => $r['success']));
        $this->assertGreaterThan($certificateCount * 0.9, $successCount, "90% dos certificados devem ser gerados com sucesso");

        // Verificar tempo médio por certificado
        $avgTime = array_sum(array_column($results, 'time')) / count($results);
        $this->assertLessThan(0.3, $avgTime, "Tempo médio por certificado deve ser menor que 0.3 segundos");
    }

    /**
     * Teste de carga para notificações
     */
    public function test_notification_service_load(): void
    {
        // Arrange
        $notificationCount = 500;
        $users = User::factory()->count(100)->create();

        $dispatcher = $this->createMock(Dispatcher::class);
        $mailer = $this->createMock(Mailer::class);
        $mailer->method('to')->willReturnSelf();
        $mailer->method('send')->willReturn(null);

        $notificationService = new NotificationService($dispatcher, $mailer);

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular envio de múltiplas notificações
        $results = [];
        
        for ($i = 0; $i < $notificationCount; $i++) {
            $user = $users->random();
            
            $operationStart = microtime(true);
            
            // Enviar notificação
            $notification = $notificationService->sendToUser(
                $user,
                "Notificação {$i}",
                "Conteúdo da notificação {$i}",
                'info',
                'bell',
                'blue'
            );
            
            $operationTime = microtime(true) - $operationStart;
            $results[] = [
                'time' => $operationTime,
                'success' => $notification !== null
            ];
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        $this->assertLessThan(20.0, $totalTime, "500 notificações devem ser enviadas em menos de 20 segundos");
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 50MB");

        // Verificar que notificações foram criadas
        $successCount = count(array_filter($results, fn($r) => $r['success']));
        $this->assertEquals($notificationCount, $successCount, "Todas as notificações devem ser criadas com sucesso");

        // Verificar tempo médio por notificação
        $avgTime = array_sum(array_column($results, 'time')) / count($results);
        $this->assertLessThan(0.05, $avgTime, "Tempo médio por notificação deve ser menor que 0.05 segundos");
    }

    /**
     * Teste de carga para consultas complexas
     */
    public function test_complex_queries_load(): void
    {
        // Arrange
        $queryCount = 200;
        $users = User::factory()->count(50)->create();
        $modules = Module::factory()->count(20)->create();

        // Criar dados de teste
        foreach ($users as $user) {
            foreach ($modules->random(5) as $module) {
                \App\Models\UserProgress::create([
                    'user_id' => $user->id,
                    'module_id' => $module->id,
                    'status' => ['not_started', 'in_progress', 'completed'][rand(0, 2)],
                    'progress_percentage' => rand(0, 100)
                ]);
            }
        }

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular consultas complexas
        $results = [];
        
        for ($i = 0; $i < $queryCount; $i++) {
            $queryStart = microtime(true);
            
            // Consulta complexa: usuários com progresso em módulos
            $userProgress = \App\Models\UserProgress::with(['user', 'module'])
                ->where('status', 'completed')
                ->where('progress_percentage', '>=', 80)
                ->orderBy('completed_at', 'desc')
                ->limit(10)
                ->get();
            
            $queryTime = microtime(true) - $queryStart;
            $results[] = $queryTime;
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        $this->assertLessThan(15.0, $totalTime, "200 consultas complexas devem ser executadas em menos de 15 segundos");
        $this->assertLessThan(100 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 100MB");

        // Verificar tempo médio por consulta
        $avgTime = array_sum($results) / count($results);
        $this->assertLessThan(0.1, $avgTime, "Tempo médio por consulta deve ser menor que 0.1 segundos");
    }

    /**
     * Teste de carga para operações de cache
     */
    public function test_cache_operations_load(): void
    {
        // Arrange
        $operationCount = 1000;
        $cacheKey = 'load_test_cache';

        $startTime = microtime(true);
        $memoryStart = memory_get_usage();

        // Act - Simular operações de cache
        $results = [];
        
        for ($i = 0; $i < $operationCount; $i++) {
            $operationStart = microtime(true);
            
            // Operação de cache
            $key = "{$cacheKey}_{$i}";
            cache()->put($key, "value_{$i}", 300);
            $value = cache()->get($key);
            cache()->forget($key);
            
            $operationTime = microtime(true) - $operationStart;
            $results[] = $operationTime;
        }

        $endTime = microtime(true);
        $memoryEnd = memory_get_usage();

        // Assert
        $totalTime = $endTime - $startTime;
        $memoryUsed = $memoryEnd - $memoryStart;

        $this->assertLessThan(10.0, $totalTime, "1000 operações de cache devem ser completadas em menos de 10 segundos");
        $this->assertLessThan(50 * 1024 * 1024, $memoryUsed, "Uso de memória deve ser menor que 50MB");

        // Verificar tempo médio por operação
        $avgTime = array_sum($results) / count($results);
        $this->assertLessThan(0.01, $avgTime, "Tempo médio por operação de cache deve ser menor que 0.01 segundos");
    }
} 