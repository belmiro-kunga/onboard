<?php

/**
 * FASE 3.4: Aplicar Repositories Completamente
 * Substitui queries duplicadas pelos métodos dos repositories
 */

echo "🚀 FASE 3.4: APLICANDO REPOSITORIES COMPLETAMENTE...\n\n";

$basePath = dirname(__DIR__);

// Atualizar Service Provider para incluir todos os repositories
updateRepositoryServiceProvider($basePath);

// Aplicar repositories nos controllers
applyRepositoriesToControllers($basePath);

// Aplicar repositories nos services
applyRepositoriesToServices($basePath);

echo "\n✅ FASE 3.4 concluída! Repositories aplicados completamente.\n";

function updateRepositoryServiceProvider($basePath) {
    echo "📊 Atualizando RepositoryServiceProvider...\n";
    
    $providerPath = $basePath . '/app/Providers/RepositoryServiceProvider.php';
    $content = file_get_contents($providerPath);

    // Adicionar todos os repositories se não existirem
    $repositories = [
        'QuizRepository',
        'SimuladoRepository',
        'ProgressRepository'
    ];

    foreach ($repositories as $repo) {
        if (strpos($content, $repo) === false) {
            // Adicionar import
            $content = str_replace(
                'use App\Repositories\NotificationRepository;',
                "use App\Repositories\NotificationRepository;\nuse App\Repositories\\{$repo};",
                $content
            );

            // Adicionar binding
            $content = str_replace(
                'return new NotificationRepository();
        });',
                "return new NotificationRepository();
        });

        \$this->app->singleton({$repo}::class, function (\$app) {
            return new {$repo}();
        });",
                $content
            );
        }
    }

    file_put_contents($providerPath, $content);
    echo "  ✅ RepositoryServiceProvider atualizado\n";
}

function applyRepositoriesToControllers($basePath) {
    echo "📊 Aplicando repositories nos controllers...\n";
    
    $controllerMappings = [
        'Admin/SimuladoController.php' => 'SimuladoRepository',
        'SimuladoController.php' => 'SimuladoRepository',
        'Admin/QuizController.php' => 'QuizRepository',
        'QuizController.php' => 'QuizRepository',
        'QuizAnswerController.php' => 'QuizRepository',
        'ProgressController.php' => 'ProgressRepository',
        'GamificationController.php' => 'ProgressRepository',
        'NotificationController.php' => 'NotificationRepository',
        'Admin/UserDashboardController.php' => 'UserRepository',
        'Admin/DashboardController.php' => 'ModuleRepository'
    ];

    foreach ($controllerMappings as $controller => $repository) {
        applyRepositoryToController($controller, $repository, $basePath);
    }
}

function applyRepositoryToController($controllerPath, $repositoryName, $basePath) {
    $fullPath = $basePath . "/app/Http/Controllers/{$controllerPath}";
    
    if (!file_exists($fullPath)) {
        return;
    }

    $content = file_get_contents($fullPath);
    $originalContent = $content;

    // Adicionar import do repository
    if (strpos($content, "use App\\Repositories\\{$repositoryName};") === false) {
        $content = preg_replace(
            '/(namespace[^;]+;)(\s*\n)/',
            '$1$2' . "\nuse App\\Repositories\\{$repositoryName};",
            $content,
            1
        );
    }

    // Substituir queries específicas baseadas no repository
    $content = replaceQueriesWithRepository($content, $repositoryName);

    if ($content !== $originalContent) {
        file_put_contents($fullPath, $content);
        echo "  ✅ {$controllerPath}: {$repositoryName} aplicado\n";
    }
}

function replaceQueriesWithRepository($content, $repositoryName) {
    switch ($repositoryName) {
        case 'SimuladoRepository':
            $replacements = [
                '/SimuladoTentativa::where\(\'simulado_id\', \$([^)]+)\)->exists\(\)/' => '$this->simuladoRepository->hasAttempts($1)',
                '/SimuladoTentativa::where\(\'id\', \(int\) \$([^)]+)\)\s*->where\(\'user_id\', \$([^)]+)\)\s*->where\(\'simulado_id\', \$([^)]+)\)\s*->first\(\)/' => '$this->simuladoRepository->getUserAttempt($3, $2, $1)'
            ];
            break;
            
        case 'QuizRepository':
            $replacements = [
                '/QuizAttempt::where\(\'user_id\', \$([^)]+)\)\s*->where\(\'created_at\', \'>=\', \$([^)]+)\)\s*->get\(\)/' => '$this->quizRepository->getUserAttempts($1, $2)',
                '/QuizAttemptAnswer::where\(\'attempt_id\', \$([^)]+)\)\s*->where\(\'question_id\', \$([^)]+)\)\s*->first\(\)/' => '$this->quizRepository->getAttemptAnswer($1, $2)'
            ];
            break;
            
        case 'ProgressRepository':
            $replacements = [
                '/UserProgress::where\(\'user_id\', \$([^)]+)\)\s*->where\(\'module_id\', \$([^)]+)\)\s*->first\(\)/' => '$this->progressRepository->getUserModuleProgress($1, $2)',
                '/UserGamification::where\(\'user_id\', \$([^)]+)\)->first\(\)/' => '$this->progressRepository->getUserGamification($1)'
            ];
            break;
            
        case 'NotificationRepository':
            $replacements = [
                '/Notification::where\(\'user_id\', \$([^)]+)\)\s*->where\(\'id\', \$([^)]+)\)\s*->firstOrFail\(\)/' => '$this->notificationRepository->findByUserAndId($1, $2)',
                '/Notification::where\(\'user_id\', \$([^)]+)\)\s*->orderBy\(\'created_at\', \'desc\'\)\s*->limit\(5\)\s*->get\(\)/' => '$this->notificationRepository->getByUser($1, 5)'
            ];
            break;
            
        case 'UserRepository':
            $replacements = [
                '/User::where\(\'role\', \'admin\'\)->count\(\)/' => '$this->userRepository->countAdmins()'
            ];
            break;
            
        case 'ModuleRepository':
            $replacements = [
                '/Module::where\(\'is_active\', true\)->count\(\)/' => '$this->moduleRepository->countActive()',
                '/Module::where\(\'is_active\', true\)\s*->orderBy\(\'order_index\'\)\s*->get\(\)/' => '$this->moduleRepository->getActiveOrdered()'
            ];
            break;
            
        default:
            $replacements = [];
    }

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    return $content;
}

function applyRepositoriesToServices($basePath) {
    echo "📊 Aplicando repositories nos services...\n";
    
    $servicesToUpdate = [
        'CertificateService.php' => 'ModuleRepository',
        'NotificationService.php' => 'NotificationRepository',
        'CalendarService.php' => 'ProgressRepository'
    ];

    foreach ($servicesToUpdate as $service => $repository) {
        applyRepositoryToService($service, $repository, $basePath);
    }
}

function applyRepositoryToService($servicePath, $repositoryName, $basePath) {
    $fullPath = $basePath . "/app/Services/{$servicePath}";
    
    if (!file_exists($fullPath)) {
        return;
    }

    $content = file_get_contents($fullPath);
    $originalContent = $content;

    // Adicionar import do repository
    if (strpos($content, "use App\\Repositories\\{$repositoryName};") === false) {
        $content = preg_replace(
            '/(namespace[^;]+;)(\s*\n)/',
            '$1$2' . "\nuse App\\Repositories\\{$repositoryName};",
            $content,
            1
        );
    }

    // Substituir queries específicas
    $content = replaceQueriesWithRepository($content, $repositoryName);

    if ($content !== $originalContent) {
        file_put_contents($fullPath, $content);
        echo "  ✅ {$servicePath}: {$repositoryName} aplicado\n";
    }
}