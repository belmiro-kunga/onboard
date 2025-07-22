<?php

/**
 * FASE 4.6: Aplicar Repositories Melhorados
 * Substitui as queries duplicadas restantes pelos novos métodos dos repositories
 */

echo "🚀 FASE 4.6: APLICANDO REPOSITORIES MELHORADOS...\n\n";

$basePath = dirname(__DIR__);
$fixedCount = 0;

// Aplicar métodos específicos do QuizRepository
applyEnhancedQuizRepository($basePath);

// Aplicar métodos específicos do SimuladoRepository
applyEnhancedSimuladoRepository($basePath);

// Aplicar métodos específicos do ModuleRepository
applyEnhancedModuleRepository($basePath);

// Aplicar UserRepository para queries de usuários ativos
applyUserRepositoryForActiveUsers($basePath);

echo "\n✅ FASE 4.6 concluída! Repositories melhorados aplicados.\n";
echo "📊 Total de controllers atualizados: {$fixedCount}\n";

function applyEnhancedQuizRepository($basePath) {
    echo "📊 Aplicando QuizRepository melhorado...\n";
    global $fixedCount;
    
    $controllersToUpdate = [
        'QuizAnswerController.php'
    ];
    
    foreach ($controllersToUpdate as $controller) {
        updateControllerWithEnhancedQuizRepo($controller, $basePath);
        $fixedCount++;
    }
}

function updateControllerWithEnhancedQuizRepo($controllerName, $basePath) {
    $controllerPath = $basePath . "/app/Http/Controllers/{$controllerName}";
    
    if (!file_exists($controllerPath)) {
        return;
    }

    $content = file_get_contents($controllerPath);
    $originalContent = $content;

    // Substituir queries específicas pelos métodos do repository
    $replacements = [
        // QuizAttemptAnswer::where('attempt_id', $attempt->id)->where('question_id', $question->id)->first()
        '/QuizAttemptAnswer::where\(\'attempt_id\', \$([^)]+)->id\)\s*->where\(\'question_id\', \$([^)]+)->id\)\s*->first\(\)/' => '$this->quizRepository->getAttemptAnswer($1->id, $2->id)',
        
        // Verificações de acesso e status
        '/\$attempt->status === \'completed\'/' => '$this->quizRepository->isAttemptCompleted($attempt->id)',
        '/\$attempt->user_id !== \$user->id/' => '!$this->quizRepository->canAccessAttempt($attempt->id, $user->id)'
    ];

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    if ($content !== $originalContent) {
        file_put_contents($controllerPath, $content);
        echo "  ✅ {$controllerName}: QuizRepository melhorado aplicado\n";
    }
}

function applyEnhancedSimuladoRepository($basePath) {
    echo "📊 Aplicando SimuladoRepository melhorado...\n";
    global $fixedCount;
    
    $controllersToUpdate = [
        'SimuladoController.php',
        'Admin/SimuladoController.php'
    ];
    
    foreach ($controllersToUpdate as $controller) {
        updateControllerWithEnhancedSimuladoRepo($controller, $basePath);
        $fixedCount++;
    }
}

function updateControllerWithEnhancedSimuladoRepo($controllerName, $basePath) {
    $controllerPath = $basePath . "/app/Http/Controllers/{$controllerName}";
    
    if (!file_exists($controllerPath)) {
        return;
    }

    $content = file_get_contents($controllerPath);
    $originalContent = $content;

    // Substituir queries complexas pelos métodos do repository
    $replacements = [
        // SimuladoTentativa::where('id', (int) $tentativaId)->where('user_id', $user->id)->where('simulado_id', $simulado->id)->first()
        '/SimuladoTentativa::where\(\'id\', \(int\) \$([^)]+)\)\s*->where\(\'user_id\', \$([^)]+)->id\)\s*->where\(\'simulado_id\', \$([^)]+)->id\)\s*->first\(\)/' => '$this->simuladoRepository->getValidatedAttempt($1, $2->id, $3->id)',
        
        // Validações de acesso
        '/SimuladoTentativa::where\(\'id\', \(int\) \$([^)]+)\)\s*->where\(\'user_id\', \$([^)]+)->id\)\s*->where\(\'simulado_id\', \$([^)]+)->id\)\s*->exists\(\)/' => '$this->simuladoRepository->validateAttemptAccess($1, $2->id, $3->id)'
    ];

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    if ($content !== $originalContent) {
        file_put_contents($controllerPath, $content);
        echo "  ✅ {$controllerName}: SimuladoRepository melhorado aplicado\n";
    }
}

function applyEnhancedModuleRepository($basePath) {
    echo "📊 Aplicando ModuleRepository melhorado...\n";
    global $fixedCount;
    
    $controllersToUpdate = [
        'DashboardController.php',
        'ModuleController.php'
    ];
    
    foreach ($controllersToUpdate as $controller) {
        updateControllerWithEnhancedModuleRepo($controller, $basePath);
        $fixedCount++;
    }
}

function updateControllerWithEnhancedModuleRepo($controllerName, $basePath) {
    $controllerPath = $basePath . "/app/Http/Controllers/{$controllerName}";
    
    if (!file_exists($controllerPath)) {
        return;
    }

    $content = file_get_contents($controllerPath);
    $originalContent = $content;

    // Substituir queries específicas pelos métodos do repository
    $replacements = [
        // Module::where('is_active', true)->orderBy('order_index')->get()
        '/Module::where\(\'is_active\', true\)\s*->orderBy\(\'order_index\'\)\s*->get\(\)/' => '$this->moduleRepository->getActiveOrderedForDashboard()',
        
        // Module::where('is_active', true)->count()
        '/Module::where\(\'is_active\', true\)->count\(\)/' => '$this->moduleRepository->countActiveModules()'
    ];

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    // Adicionar injeção do ModuleRepository se não existir
    if (strpos($content, 'ModuleRepository') === false && $content !== $originalContent) {
        $content = preg_replace(
            '/(namespace[^;]+;)(\s*\n)/',
            '$1$2' . "\nuse App\\Repositories\\ModuleRepository;",
            $content,
            1
        );
    }

    if ($content !== $originalContent) {
        file_put_contents($controllerPath, $content);
        echo "  ✅ {$controllerName}: ModuleRepository melhorado aplicado\n";
    }
}

function applyUserRepositoryForActiveUsers($basePath) {
    echo "📊 Aplicando UserRepository para usuários ativos...\n";
    global $fixedCount;
    
    $controllersToUpdate = [
        'Admin/UserDashboardController.php'
    ];
    
    foreach ($controllersToUpdate as $controller) {
        updateControllerWithUserRepo($controller, $basePath);
        $fixedCount++;
    }
}

function updateControllerWithUserRepo($controllerName, $basePath) {
    $controllerPath = $basePath . "/app/Http/Controllers/{$controllerName}";
    
    if (!file_exists($controllerPath)) {
        return;
    }

    $content = file_get_contents($controllerPath);
    $originalContent = $content;

    // Substituir queries de usuários ativos
    $replacements = [
        '/User::where\(\'is_active\', true\)->count\(\)/' => '$this->userRepository->countActive()'
    ];

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    if ($content !== $originalContent) {
        file_put_contents($controllerPath, $content);
        echo "  ✅ {$controllerName}: UserRepository aplicado\n";
    }
}