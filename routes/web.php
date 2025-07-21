<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\QuizQuestionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página de boas-vindas pública
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Rota para renovar token CSRF (sem middleware de autenticação para funcionar sempre)
Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf.token');

// Rotas autenticadas
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard principal
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    
    // Rota de teste
    Route::get('/test', function () {
        return view('test');
    });
    
    // Módulos de aprendizado
    Route::get('/modules', [App\Http\Controllers\ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/{module}', [App\Http\Controllers\ModuleController::class, 'show'])->name('modules.show');
    
    // Tracking de progresso
    Route::post('/modules/{module}/content/{content}/viewed', [App\Http\Controllers\ModuleController::class, 'markContentAsViewed'])
        ->name('modules.content.viewed');
    Route::post('/modules/{module}/quiz/{quiz}/completed', [App\Http\Controllers\ModuleController::class, 'markQuizAsCompleted'])
        ->name('modules.quiz.completed');
    Route::post('/modules/{module}/notes', [App\Http\Controllers\ModuleController::class, 'saveNotes'])
        ->name('modules.notes.save');
    
    // Gamificação
    Route::get('/gamification', [App\Http\Controllers\GamificationController::class, 'dashboard'])->name('gamification.dashboard');
    Route::get('/gamification/ranking', [App\Http\Controllers\GamificationController::class, 'ranking'])->name('gamification.ranking');
    Route::get('/gamification/achievements', [App\Http\Controllers\GamificationController::class, 'achievements'])->name('gamification.achievements');
    Route::get('/gamification/avatar', function () {
        return view('gamification.avatar-customization');
    })->name('gamification.avatar');
    
    // Sistema de Quizzes
    Route::prefix('quizzes')->name('quizzes.')->group(function () {
        Route::get('/', [App\Http\Controllers\QuizController::class, 'index'])->name('index');
        Route::get('/{quiz}', [App\Http\Controllers\QuizController::class, 'show'])->name('show');
        Route::get('/{quiz}/start', [App\Http\Controllers\QuizController::class, 'showStartPage'])->name('start.show');
        Route::post('/{quiz}/start', [App\Http\Controllers\QuizController::class, 'startAttempt'])->name('start');
        Route::get('/{quiz}/attempt/{attempt}', [App\Http\Controllers\QuizController::class, 'attempt'])->name('attempt');
        Route::post('/{quiz}/attempt/{attempt}/submit', [App\Http\Controllers\QuizController::class, 'submitAttempt'])->name('submit');
        Route::get('/{quiz}/attempt/{attempt}/results', [App\Http\Controllers\QuizController::class, 'results'])->name('results');
        Route::get('/{quiz}/statistics', [App\Http\Controllers\QuizController::class, 'statistics'])->name('statistics');
        Route::get('/ranking/global', [App\Http\Controllers\QuizController::class, 'ranking'])->name('ranking');
        
        // Rotas para feedback imediato
        Route::post('/{quiz}/attempt/{attempt}/answer', [App\Http\Controllers\QuizAnswerController::class, 'saveAnswer'])->name('answer.save');
        Route::get('/{quiz}/attempt/{attempt}/feedback/{question}', [App\Http\Controllers\QuizAnswerController::class, 'getFeedback'])->name('answer.feedback');
        Route::post('/{quiz}/attempt/{attempt}/next', [App\Http\Controllers\QuizAnswerController::class, 'nextQuestion'])->name('answer.next');
    });

    // Sistema de Certificados
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [App\Http\Controllers\CertificateController::class, 'index'])->name('index');
        Route::get('/{certificate}', [App\Http\Controllers\CertificateController::class, 'show'])->name('show');
        Route::get('/{certificate}/download', [App\Http\Controllers\CertificateController::class, 'download'])->name('download');
        Route::get('/verify/{code}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('verify');
        Route::post('/request', [App\Http\Controllers\CertificateController::class, 'request'])->name('request');
    });

    // Sistema de Simulados
    Route::prefix('simulados')->name('simulados.')->group(function () {
        Route::get('/', [App\Http\Controllers\SimuladoController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\SimuladoController::class, 'history'])->name('history');
        Route::get('/{id}', [App\Http\Controllers\SimuladoController::class, 'show'])->name('show');
        Route::post('/{id}/start', [App\Http\Controllers\SimuladoController::class, 'start'])->name('start');
        Route::get('/{id}/attempt/{tentativa}', [App\Http\Controllers\SimuladoController::class, 'attempt'])->name('attempt');
        Route::post('/{id}/attempt/{tentativa}/answer', [App\Http\Controllers\SimuladoController::class, 'saveAnswer'])->name('answer');
        Route::post('/{id}/attempt/{tentativa}/finish', [App\Http\Controllers\SimuladoController::class, 'finish'])->name('finish');
        Route::get('/{id}/attempt/{tentativa}/result', [App\Http\Controllers\SimuladoController::class, 'result'])->name('result');
        Route::get('/{id}/attempt/{tentativa}/report', [App\Http\Controllers\SimuladoController::class, 'report'])->name('report');
    });

    // Rastreamento de Vídeos
    Route::prefix('video-tracking')->name('video.')->group(function () {
        Route::post('/progress', [App\Http\Controllers\VideoTrackingController::class, 'trackProgress'])->name('track.progress');
        Route::post('/completed', [App\Http\Controllers\VideoTrackingController::class, 'markCompleted'])->name('mark.completed');
    });

    // Qualificador Ocupacional
    Route::prefix('occupational-qualifier')->name('occupational-qualifier.')->group(function () {
        Route::get('/', [App\Http\Controllers\OccupationalQualifierController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\OccupationalQualifierController::class, 'show'])->name('show');
        Route::post('/{id}/submit', [App\Http\Controllers\OccupationalQualifierController::class, 'submitResult'])->name('submit');
        Route::get('/{id}/result', [App\Http\Controllers\OccupationalQualifierController::class, 'showResult'])->name('result');
    });

    // Sistema de Acompanhamento de Progresso
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProgressController::class, 'index'])->name('index');
    });

    // Sistema de Analytics Pessoais
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('index');
    });

    // Perfil do Usuário
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });

    // Sistema de Notificações
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
        Route::get('/{notification}', [App\Http\Controllers\NotificationController::class, 'show'])->name('show');
        Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
        
        // API endpoints
        Route::get('/api/stats', [App\Http\Controllers\NotificationController::class, 'stats'])->name('api.stats');
        Route::get('/api/latest', [App\Http\Controllers\NotificationController::class, 'latest'])->name('api.latest');
        
        // Admin/Manager endpoints
        Route::post('/api/send-manager-message', [App\Http\Controllers\NotificationController::class, 'sendManagerMessage'])->name('api.send-manager-message');
        Route::post('/api/send-bulk', [App\Http\Controllers\NotificationController::class, 'sendBulkNotification'])->name('api.send-bulk');
    });

    // Painel Administrativo
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard Administrativo
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Gerenciamento de Usuários
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
        Route::post('users/{user}/toggle-active', [App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('users/bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action');
        
        // Gerenciamento de Módulos
        Route::resource('modules', App\Http\Controllers\Admin\ModuleController::class);
        Route::post('modules/{module}/toggle-active', [App\Http\Controllers\Admin\ModuleController::class, 'toggleActive'])->name('modules.toggle-active');
        Route::post('modules/reorder', [App\Http\Controllers\Admin\ModuleController::class, 'reorder'])->name('modules.reorder');
        Route::get('modules/{module}/contents', [App\Http\Controllers\Admin\ModuleController::class, 'contents'])->name('modules.contents');
        Route::post('modules/{module}/contents', [App\Http\Controllers\Admin\ModuleController::class, 'addContent'])->name('modules.contents.add');
        Route::delete('modules/{module}/contents/{content}', [App\Http\Controllers\Admin\ModuleController::class, 'removeContent'])->name('modules.contents.remove');
        
        // Gerenciamento de Certificados
        Route::resource('certificates', App\Http\Controllers\Admin\CertificateController::class);
        Route::get('certificates/{certificate}/generate-pdf', [App\Http\Controllers\Admin\CertificateController::class, 'generatePdf'])->name('certificates.generate-pdf');
        Route::get('certificates/{certificate}/download-pdf', [App\Http\Controllers\Admin\CertificateController::class, 'downloadPdf'])->name('certificates.download-pdf');
        Route::post('certificates/{certificate}/send-email', [App\Http\Controllers\Admin\CertificateController::class, 'sendByEmail'])->name('certificates.send-email');
        Route::get('certificates/verify', [App\Http\Controllers\Admin\CertificateController::class, 'verify'])->name('certificates.verify');
        
        // Gerenciamento de Simulados
        Route::resource('simulados', App\Http\Controllers\Admin\SimuladoController::class);
        Route::post('simulados/{simulado}/toggle-active', [App\Http\Controllers\Admin\SimuladoController::class, 'toggleActive'])->name('simulados.toggle-active');
        Route::get('simulados/{simulado}/questoes', [App\Http\Controllers\Admin\SimuladoController::class, 'questoes'])->name('simulados.questoes');
        Route::get('simulados/{simulado}/questoes/create', [App\Http\Controllers\Admin\SimuladoController::class, 'createQuestao'])->name('simulados.questoes.create');
        Route::post('simulados/{simulado}/questoes', [App\Http\Controllers\Admin\SimuladoController::class, 'storeQuestao'])->name('simulados.questoes.store');
        Route::get('simulados/{simulado}/questoes/{questao}/edit', [App\Http\Controllers\Admin\SimuladoController::class, 'editQuestao'])->name('simulados.questoes.edit');
        Route::put('simulados/{simulado}/questoes/{questao}', [App\Http\Controllers\Admin\SimuladoController::class, 'updateQuestao'])->name('simulados.questoes.update');
        Route::delete('simulados/{simulado}/questoes/{questao}', [App\Http\Controllers\Admin\SimuladoController::class, 'destroyQuestao'])->name('simulados.questoes.destroy');
        Route::post('simulados/{simulado}/questoes/reorder', [App\Http\Controllers\Admin\SimuladoController::class, 'reorderQuestoes'])->name('simulados.questoes.reorder');
        Route::get('simulados/{simulado}/atribuicoes', [App\Http\Controllers\Admin\SimuladoController::class, 'atribuicoes'])->name('simulados.atribuicoes');
        Route::post('simulados/{simulado}/atribuir', [App\Http\Controllers\Admin\SimuladoController::class, 'atribuir'])->name('simulados.atribuir');
        Route::get('simulados/{simulado}/resultados', [App\Http\Controllers\Admin\SimuladoController::class, 'resultados'])->name('simulados.resultados');
        Route::get('simulados/{simulado}/tentativas/{tentativa}', [App\Http\Controllers\Admin\SimuladoController::class, 'tentativa'])->name('simulados.tentativa');
        
        // Relatórios
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
        
        // Gerenciamento de Quizzes
        Route::prefix('quizzes')->name('quizzes.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\QuizController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\QuizController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\QuizController::class, 'store'])->name('store');
            Route::get('/{quiz}', [App\Http\Controllers\Admin\QuizController::class, 'show'])->name('show');
            Route::get('/{quiz}/edit', [App\Http\Controllers\Admin\QuizController::class, 'edit'])->name('edit');
            Route::put('/{quiz}', [App\Http\Controllers\Admin\QuizController::class, 'update'])->name('update');
            Route::delete('/{quiz}', [App\Http\Controllers\Admin\QuizController::class, 'destroy'])->name('destroy');
            Route::post('/{quiz}/duplicate', [App\Http\Controllers\Admin\QuizController::class, 'duplicate'])->name('duplicate');
            Route::get('/{quiz}/preview', [App\Http\Controllers\Admin\QuizController::class, 'preview'])->name('preview');
            Route::get('/{quiz}/questions', [App\Http\Controllers\Admin\QuizController::class, 'questions'])->name('questions');
            Route::post('/{quiz}/questions/reorder', [App\Http\Controllers\Admin\QuizController::class, 'reorderQuestions'])->name('questions.reorder');
            Route::get('/{quiz}/reports', [App\Http\Controllers\Admin\QuizController::class, 'reports'])->name('reports');
        });

        // Gerenciamento de Questões
        Route::prefix('quizzes/{quiz}/questions')->name('quiz-questions.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\QuizQuestionController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\QuizQuestionController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Admin\QuizQuestionController::class, 'store'])->name('store');
            Route::get('/{question}', [App\Http\Controllers\Admin\QuizQuestionController::class, 'show'])->name('show');
            Route::get('/{question}/edit', [App\Http\Controllers\Admin\QuizQuestionController::class, 'edit'])->name('edit');
            Route::put('/{question}', [App\Http\Controllers\Admin\QuizQuestionController::class, 'update'])->name('update');
            Route::delete('/{question}', [App\Http\Controllers\Admin\QuizQuestionController::class, 'destroy'])->name('destroy');
            Route::post('/{question}/duplicate', [App\Http\Controllers\Admin\QuizQuestionController::class, 'duplicate'])->name('duplicate');
            Route::post('/{question}/toggle-active', [App\Http\Controllers\Admin\QuizQuestionController::class, 'toggleActive'])->name('toggle-active');
            Route::get('/{question}/preview', [App\Http\Controllers\Admin\QuizQuestionController::class, 'preview'])->name('preview');
            Route::post('/import', [App\Http\Controllers\Admin\QuizQuestionController::class, 'import'])->name('import');
        });
    });
    
    // Rota de teste para menu colapsável
    Route::get('/test-collapsible', function () {
        return view('test-collapsible');
    })->name('test.collapsible');

});