<?php

/**
 * Script para aplicar traits avançados aos models
 */

echo "📊 Aplicando traits avançados aos models...\n";

$basePath = dirname(__DIR__);

// Models que podem usar HasCommonScopes
$modelsForScopes = [
    'CalendarEvent.php',
    'Notification.php', 
    'QuizQuestion.php',
    'CourseEnrollment.php',
    'UserProgress.php'
];

foreach ($modelsForScopes as $model) {
    $modelPath = $basePath . "/app/Models/{$model}";
    
    if (!file_exists($modelPath)) {
        continue;
    }

    $content = file_get_contents($modelPath);
    
    // Verificar se já tem o trait
    if (strpos($content, 'HasCommonScopes') !== false) {
        continue;
    }

    // Adicionar import
    $content = preg_replace(
        '/(use App\\\\Models\\\\Traits\\\\FormattedTimestamps;)/',
        '$1' . "\nuse App\\Models\\Traits\\HasCommonScopes;",
        $content
    );

    // Se não encontrou FormattedTimestamps, adicionar após outros imports
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

    file_put_contents($modelPath, $content);
    echo "  ✅ {$model}: HasCommonScopes trait aplicado\n";
}

// Models que podem usar Cacheable
$modelsForCache = [
    'Course.php',
    'Module.php',
    'Quiz.php',
    'User.php'
];

foreach ($modelsForCache as $model) {
    $modelPath = $basePath . "/app/Models/{$model}";
    
    if (!file_exists($modelPath)) {
        continue;
    }

    $content = file_get_contents($modelPath);
    
    // Verificar se já tem o trait
    if (strpos($content, 'Cacheable') !== false) {
        continue;
    }

    // Adicionar import
    $content = preg_replace(
        '/(use App\\\\Models\\\\Traits\\\\FormattedTimestamps;)/',
        '$1' . "\nuse App\\Models\\Traits\\Cacheable;",
        $content
    );

    // Se não encontrou FormattedTimestamps, adicionar após outros imports
    if (strpos($content, 'Cacheable') === false) {
        $content = preg_replace(
            '/(use App\\\\Models\\\\Traits\\\\HasCommonScopes;)/',
            '$1' . "\nuse App\\Models\\Traits\\Cacheable;",
            $content
        );
    }

    // Se ainda não encontrou, adicionar após imports do Laravel
    if (strpos($content, 'Cacheable') === false) {
        $content = preg_replace(
            '/(use Illuminate[^;]+;)(\s*\n)/',
            '$1$2use App\\Models\\Traits\\Cacheable;' . "\n",
            $content,
            1
        );
    }

    // Adicionar trait na classe
    $content = preg_replace(
        '/(use [^;]*FormattedTimestamps[^;]*;)/',
        '$1, Cacheable',
        $content
    );

    // Se não encontrou FormattedTimestamps, adicionar ao use existente
    if (strpos($content, ', Cacheable') === false) {
        $content = preg_replace(
            '/(use HasFactory[^;]*;)/',
            '$1, Cacheable',
            $content
        );
    }

    file_put_contents($modelPath, $content);
    echo "  ✅ {$model}: Cacheable trait aplicado\n";
}

echo "✅ Traits avançados aplicados com sucesso!\n";