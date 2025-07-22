<?php

/**
 * Script para atualizar controllers restantes
 */

echo "📊 Atualizando controllers restantes...\n";

$basePath = dirname(__DIR__);

// Controllers web para atualizar
$webControllers = [
    'AnalyticsController.php',
    'WelcomeController.php', 
    'ProfileController.php',
    'OccupationalQualifierController.php',
    'VideoTrackingController.php'
];

foreach ($webControllers as $controller) {
    $controllerPath = $basePath . "/app/Http/Controllers/{$controller}";
    
    if (!file_exists($controllerPath)) {
        continue;
    }

    $content = file_get_contents($controllerPath);
    $originalContent = $content;

    // Atualizar extends
    $content = preg_replace(
        '/class (\w+) extends Controller/',
        'class $1 extends BaseController',
        $content
    );

    // Atualizar import
    $content = str_replace(
        'use App\Http\Controllers\Controller;',
        'use App\Http\Controllers\BaseController;',
        $content
    );

    // Padronizar responses
    $content = preg_replace(
        '/return redirect\(\)->back\(\)->with\([\'"]success[\'"], ([^)]+)\);/',
        'return $this->backWithSuccess($1);',
        $content
    );

    $content = preg_replace(
        '/return redirect\(\)->back\(\)->with\([\'"]error[\'"], ([^)]+)\);/',
        'return $this->backWithError($1);',
        $content
    );

    if ($content !== $originalContent) {
        file_put_contents($controllerPath, $content);
        echo "  ✅ {$controller}: Atualizado para BaseController\n";
    }
}

// Controllers admin para atualizar
$adminControllers = [
    'DashboardController.php',
    'ReportController.php'
];

foreach ($adminControllers as $controller) {
    $controllerPath = $basePath . "/app/Http/Controllers/Admin/{$controller}";
    
    if (!file_exists($controllerPath)) {
        continue;
    }

    $content = file_get_contents($controllerPath);
    $originalContent = $content;

    // Atualizar extends
    $content = preg_replace(
        '/class (\w+) extends Controller/',
        'class $1 extends BaseAdminController',
        $content
    );

    // Atualizar import
    $content = str_replace(
        'use App\Http\Controllers\Controller;',
        'use App\Http\Controllers\Admin\BaseAdminController;',
        $content
    );

    if ($content !== $originalContent) {
        file_put_contents($controllerPath, $content);
        echo "  ✅ Admin/{$controller}: Atualizado para BaseAdminController\n";
    }
}

echo "✅ Controllers restantes atualizados com sucesso!\n";