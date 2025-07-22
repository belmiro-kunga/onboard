<?php

/**
 * FASE 3.2: Limpar Scopes Duplicados
 * Remove scopes duplicados dos models que já existem nos traits
 */

echo "🚀 FASE 3.2: LIMPANDO SCOPES DUPLICADOS...\n\n";

$basePath = dirname(__DIR__);

// Models que têm scopes duplicados com traits
$modelsToClean = [
    'CalendarEvent.php' => ['ByType', 'ByStatus'],
    'Notification.php' => ['ByType', 'Valid'],
    'QuizQuestion.php' => ['ByType'],
    'CourseEnrollment.php' => ['ByStatus', 'Completed', 'InProgress'],
    'UserProgress.php' => ['Completed', 'InProgress'],
    'Achievement.php' => ['ByCategory'],
    'Module.php' => ['ByCategory'],
    'Course.php' => ['ByType'],
    'ModuleContent.php' => ['ByType']
];

echo "📊 Removendo scopes duplicados dos models...\n";

foreach ($modelsToClean as $model => $scopes) {
    cleanModelScopes($model, $scopes, $basePath);
}

// Aplicar HasCommonScopes em models que ainda não têm
$modelsForCommonScopes = [
    'Certificate.php',
    'Quiz.php',
    'Simulado.php'
];

echo "\n📊 Aplicando HasCommonScopes em models adicionais...\n";

foreach ($modelsForCommonScopes as $model) {
    applyCommonScopesToModel($model, $basePath);
}

echo "\n✅ FASE 3.2 concluída! Scopes duplicados removidos.\n";

function cleanModelScopes($modelName, $scopesToRemove, $basePath) {
    $modelPath = $basePath . "/app/Models/{$modelName}";
    
    if (!file_exists($modelPath)) {
        return;
    }

    $content = file_get_contents($modelPath);
    $originalContent = $content;
    $removedCount = 0;

    foreach ($scopesToRemove as $scope) {
        // Padrões para encontrar e remover scopes
        $patterns = [
            // Scope com comentário
            '/\/\*\*[^*]*\*\/\s*public function scope' . $scope . '\([^}]+\}[^}]*\}/s',
            // Scope simples
            '/public function scope' . $scope . '\([^}]+\}/s',
            // Scope já comentado
            '/\/\/ Scope ' . $scope . '[^\n]*\n/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, "// Scope {$scope} disponível via trait HasCommonScopes\n", $content);
                $removedCount++;
                break;
            }
        }
    }

    if ($content !== $originalContent) {
        file_put_contents($modelPath, $content);
        echo "  ✅ {$modelName}: {$removedCount} scopes removidos\n";
    }
}

function applyCommonScopesToModel($modelName, $basePath) {
    $modelPath = $basePath . "/app/Models/{$modelName}";
    
    if (!file_exists($modelPath)) {
        return;
    }

    $content = file_get_contents($modelPath);
    
    // Verificar se já tem o trait
    if (strpos($content, 'HasCommonScopes') !== false) {
        return;
    }

    // Adicionar import do trait
    $content = preg_replace(
        '/(use App\\\\Models\\\\Traits\\\\FormattedTimestamps;)/',
        '$1' . "\nuse App\\Models\\Traits\\HasCommonScopes;",
        $content
    );

    // Se não encontrou FormattedTimestamps, adicionar após outros imports de traits
    if (strpos($content, 'HasCommonScopes') === false) {
        $content = preg_replace(
            '/(use App\\\\Models\\\\Traits\\\\[^;]+;)/',
            '$1' . "\nuse App\\Models\\Traits\\HasCommonScopes;",
            $content,
            1
        );
    }

    // Se ainda não encontrou, adicionar após imports do Laravel
    if (strpos($content, 'HasCommonScopes') === false) {
        $content = preg_replace(
            '/(use Illuminate[^;]+;)(\s*\n)/',
            '$1$2use App\\Models\\Traits\\HasCommonScopes;' . "\n",
            $content,
            1
        );
    }

    // Adicionar trait na classe
    $content = preg_replace(
        '/(use HasFactory[^;]*;)/',
        '$1, HasCommonScopes',
        $content
    );

    // Se não encontrou HasFactory, tentar outros padrões
    if (strpos($content, ', HasCommonScopes') === false) {
        $content = preg_replace(
            '/(use [^;]*FormattedTimestamps[^;]*;)/',
            '$1, HasCommonScopes',
            $content
        );
    }

    file_put_contents($modelPath, $content);
    echo "  ✅ {$modelName}: HasCommonScopes trait aplicado\n";
}