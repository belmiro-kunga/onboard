<?php

declare(strict_types=1);

namespace Tests\Visual;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Certificate;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VisualRegressionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Teste de regressão visual para dashboard
     */
    public function test_dashboard_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@hcp.com'
        ]);

        // Criar dados de teste
        $modules = Module::factory()->count(3)->create([
            'points_reward' => 100
        ]);

        foreach ($modules as $module) {
            \App\Models\UserProgress::create([
                'user_id' => $user->id,
                'module_id' => $module->id,
                'status' => 'completed',
                'progress_percentage' => 100
            ]);
        }

        // Act
        $response = $this->actingAs($user)->get('/dashboard');

        // Assert
        $response->assertStatus(200);

        // Verificar elementos visuais essenciais
        $this->assertVisualElements($response, [
            'dashboard-container' => 'Dashboard principal deve estar presente',
            'user-welcome' => 'Mensagem de boas-vindas deve estar presente',
            'progress-summary' => 'Resumo de progresso deve estar presente',
            'recent-activities' => 'Atividades recentes devem estar presentes',
            'quick-actions' => 'Ações rápidas devem estar presentes'
        ]);

        // Verificar estrutura HTML
        $this->assertHtmlStructure($response, [
            'main' => 'Elemento main deve estar presente',
            'header' => 'Header deve estar presente',
            'nav' => 'Navegação deve estar presente',
            'footer' => 'Footer deve estar presente'
        ]);

        // Verificar classes CSS importantes
        $this->assertCssClasses($response, [
            'bg-white' => 'Fundo branco deve estar presente',
            'text-gray-900' => 'Texto principal deve estar presente',
            'shadow-lg' => 'Sombras devem estar presentes'
        ]);
    }

    /**
     * Teste de regressão visual para módulos
     */
    public function test_modules_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();
        $modules = Module::factory()->count(5)->create([
            'category' => 'hr',
            'points_reward' => 100
        ]);

        // Act
        $response = $this->actingAs($user)->get('/modules');

        // Assert
        $response->assertStatus(200);

        // Verificar elementos visuais
        $this->assertVisualElements($response, [
            'modules-grid' => 'Grid de módulos deve estar presente',
            'module-card' => 'Cards de módulos devem estar presentes',
            'module-title' => 'Títulos dos módulos devem estar presentes',
            'module-description' => 'Descrições dos módulos devem estar presentes',
            'module-progress' => 'Barras de progresso devem estar presentes'
        ]);

        // Verificar responsividade
        $this->assertResponsiveDesign($response, [
            'sm:grid-cols-1' => 'Grid responsivo para mobile',
            'md:grid-cols-2' => 'Grid responsivo para tablet',
            'lg:grid-cols-3' => 'Grid responsivo para desktop'
        ]);

        // Verificar acessibilidade
        $this->assertAccessibility($response, [
            'aria-label' => 'Labels de acessibilidade devem estar presentes',
            'alt=' => 'Textos alternativos devem estar presentes',
            'role=' => 'Roles de acessibilidade devem estar presentes'
        ]);
    }

    /**
     * Teste de regressão visual para quizzes
     */
    public function test_quizzes_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();
        $module = Module::factory()->create();
        $quiz = Quiz::factory()->create([
            'module_id' => $module->id,
            'title' => 'Quiz de Teste',
            'passing_score' => 70
        ]);

        // Criar questões
        for ($i = 1; $i <= 5; $i++) {
            \App\Models\QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => "Questão {$i}?",
                'type' => 'multiple_choice',
                'options' => ['A' => 'Opção A', 'B' => 'Opção B', 'C' => 'Opção C'],
                'correct_answer' => 'A',
                'points' => 20,
                'order_index' => $i
            ]);
        }

        // Act
        $response = $this->actingAs($user)->get("/quizzes/{$quiz->id}");

        // Assert
        $response->assertStatus(200);

        // Verificar elementos visuais
        $this->assertVisualElements($response, [
            'quiz-container' => 'Container do quiz deve estar presente',
            'quiz-title' => 'Título do quiz deve estar presente',
            'question-container' => 'Container de questões deve estar presente',
            'answer-options' => 'Opções de resposta devem estar presentes',
            'submit-button' => 'Botão de envio deve estar presente'
        ]);

        // Verificar interatividade
        $this->assertInteractiveElements($response, [
            'radio' => 'Botões de rádio devem estar presentes',
            'checkbox' => 'Checkboxes devem estar presentes',
            'button' => 'Botões devem estar presentes'
        ]);
    }

    /**
     * Teste de regressão visual para certificados
     */
    public function test_certificates_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();
        $certificates = Certificate::factory()->count(3)->create([
            'user_id' => $user->id,
            'type' => 'module'
        ]);

        // Act
        $response = $this->actingAs($user)->get('/certificates');

        // Assert
        $response->assertStatus(200);

        // Verificar elementos visuais
        $this->assertVisualElements($response, [
            'certificates-grid' => 'Grid de certificados deve estar presente',
            'certificate-card' => 'Cards de certificados devem estar presentes',
            'certificate-title' => 'Títulos dos certificados devem estar presentes',
            'certificate-date' => 'Datas dos certificados devem estar presentes',
            'download-button' => 'Botões de download devem estar presentes'
        ]);

        // Verificar cores e temas
        $this->assertColorScheme($response, [
            'text-green-600' => 'Cores de sucesso devem estar presentes',
            'text-blue-600' => 'Cores de informação devem estar presentes',
            'text-yellow-600' => 'Cores de aviso devem estar presentes'
        ]);
    }

    /**
     * Teste de regressão visual para gamificação
     */
    public function test_gamification_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();
        $user->gamification()->create([
            'total_points' => 500,
            'current_level' => 'Explorer',
            'streak_days' => 7
        ]);

        // Act
        $response = $this->actingAs($user)->get('/gamification');

        // Assert
        $response->assertStatus(200);

        // Verificar elementos visuais
        $this->assertVisualElements($response, [
            'points-display' => 'Display de pontos deve estar presente',
            'level-indicator' => 'Indicador de nível deve estar presente',
            'progress-bar' => 'Barra de progresso deve estar presente',
            'achievements-grid' => 'Grid de achievements deve estar presente',
            'ranking-table' => 'Tabela de ranking deve estar presente'
        ]);

        // Verificar animações
        $this->assertAnimations($response, [
            'animate-pulse' => 'Animações de pulse devem estar presentes',
            'animate-bounce' => 'Animações de bounce devem estar presentes',
            'transition-all' => 'Transições devem estar presentes'
        ]);
    }

    /**
     * Teste de regressão visual para notificações
     */
    public function test_notifications_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();
        $notifications = Notification::factory()->count(5)->create([
            'user_id' => $user->id,
            'type' => 'info'
        ]);

        // Act
        $response = $this->actingAs($user)->get('/notifications');

        // Assert
        $response->assertStatus(200);

        // Verificar elementos visuais
        $this->assertVisualElements($response, [
            'notifications-list' => 'Lista de notificações deve estar presente',
            'notification-item' => 'Itens de notificação devem estar presentes',
            'notification-icon' => 'Ícones de notificação devem estar presentes',
            'notification-time' => 'Horários devem estar presentes',
            'mark-read-button' => 'Botões de marcar como lida devem estar presentes'
        ]);

        // Verificar estados visuais
        $this->assertVisualStates($response, [
            'unread' => 'Estado não lido deve estar presente',
            'read' => 'Estado lido deve estar presente',
            'urgent' => 'Estado urgente deve estar presente'
        ]);
    }

    /**
     * Teste de regressão visual para tema escuro
     */
    public function test_dark_theme_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act - Simular tema escuro
        $response = $this->actingAs($user)
            ->withHeaders(['X-Theme' => 'dark'])
            ->get('/dashboard');

        // Assert
        $response->assertStatus(200);

        // Verificar classes de tema escuro
        $this->assertCssClasses($response, [
            'dark:bg-gray-900' => 'Fundo escuro deve estar presente',
            'dark:text-white' => 'Texto claro deve estar presente',
            'dark:border-gray-700' => 'Bordas escuras devem estar presentes'
        ]);
    }

    /**
     * Teste de regressão visual para mobile
     */
    public function test_mobile_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act - Simular dispositivo mobile
        $response = $this->actingAs($user)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15'
            ])
            ->get('/dashboard');

        // Assert
        $response->assertStatus(200);

        // Verificar classes responsivas
        $this->assertCssClasses($response, [
            'sm:hidden' => 'Elementos ocultos em mobile devem estar presentes',
            'md:block' => 'Elementos visíveis em desktop devem estar presentes',
            'flex-col' => 'Layout em coluna deve estar presente'
        ]);
    }

    /**
     * Teste de regressão visual para loading states
     */
    public function test_loading_states_visual_regression(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->get('/modules');

        // Assert
        $response->assertStatus(200);

        // Verificar estados de loading
        $this->assertVisualElements($response, [
            'loading-skeleton' => 'Skeleton loading deve estar presente',
            'loading-spinner' => 'Spinner de loading deve estar presente',
            'loading-placeholder' => 'Placeholders devem estar presentes'
        ]);
    }

    // Métodos auxiliares para verificação visual

    private function assertVisualElements($response, array $elements): void
    {
        foreach ($elements as $element => $description) {
            $this->assertStringContainsString(
                $element,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertHtmlStructure($response, array $structure): void
    {
        foreach ($structure as $tag => $description) {
            $this->assertStringContainsString(
                "<{$tag}",
                $response->getContent(),
                $description
            );
        }
    }

    private function assertCssClasses($response, array $classes): void
    {
        foreach ($classes as $class => $description) {
            $this->assertStringContainsString(
                $class,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertResponsiveDesign($response, array $responsive): void
    {
        foreach ($responsive as $class => $description) {
            $this->assertStringContainsString(
                $class,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertAccessibility($response, array $accessibility): void
    {
        foreach ($accessibility as $attribute => $description) {
            $this->assertStringContainsString(
                $attribute,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertInteractiveElements($response, array $elements): void
    {
        foreach ($elements as $element => $description) {
            $this->assertStringContainsString(
                $element,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertColorScheme($response, array $colors): void
    {
        foreach ($colors as $color => $description) {
            $this->assertStringContainsString(
                $color,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertAnimations($response, array $animations): void
    {
        foreach ($animations as $animation => $description) {
            $this->assertStringContainsString(
                $animation,
                $response->getContent(),
                $description
            );
        }
    }

    private function assertVisualStates($response, array $states): void
    {
        foreach ($states as $state => $description) {
            $this->assertStringContainsString(
                $state,
                $response->getContent(),
                $description
            );
        }
    }
} 