<?php

/**
 * FASE 3.1: Consolidar Controllers 100% Similares
 * Foca nos controllers que são idênticos ou quase idênticos
 */

echo "🚀 FASE 3.1: CONSOLIDANDO CONTROLLERS SIMILARES...\n\n";

$basePath = dirname(__DIR__);
$fixedCount = 0;

// Analisar controllers 100% similares identificados
$similarControllers = [
    // Grupo 1: Controllers simples que só retornam views
    'simple_view_controllers' => [
        'AnalyticsController.php',
        'WelcomeController.php'
    ],
    // Grupo 2: Controllers de dashboard similares  
    'dashboard_controllers' => [
        'CertificateController.php',
        'DashboardController.php',
        'ModuleController.php',
        'NotificationController.php'
    ],
    // Grupo 3: Controllers de quiz similares
    'quiz_controllers' => [
        'ProgressController.php',
        'QuizController.php'
    ]
];

echo "📊 Consolidando controllers por grupos...\n";

// Consolidar controllers simples
consolidateSimpleViewControllers($similarControllers['simple_view_controllers'], $basePath);

// Consolidar controllers de dashboard
consolidateDashboardControllers($similarControllers['dashboard_controllers'], $basePath);

// Consolidar controllers de quiz
consolidateQuizControllers($similarControllers['quiz_controllers'], $basePath);

// Melhorar BaseController com métodos mais robustos
enhanceBaseController($basePath);

echo "\n✅ FASE 3.1 concluída! Controllers consolidados com sucesso.\n";

function consolidateSimpleViewControllers($controllers, $basePath) {
    echo "  📝 Consolidando controllers de view simples...\n";
    
    foreach ($controllers as $controller) {
        $controllerPath = $basePath . "/app/Http/Controllers/{$controller}";
        
        if (!file_exists($controllerPath)) {
            continue;
        }

        $content = file_get_contents($controllerPath);
        
        // Substituir por implementação padrão usando BaseController
        $newContent = createStandardViewController($controller);
        
        file_put_contents($controllerPath, $newContent);
        echo "    ✅ {$controller}: Padronizado\n";
    }
}

function consolidateDashboardControllers($controllers, $basePath) {
    echo "  📝 Consolidando controllers de dashboard...\n";
    
    foreach ($controllers as $controller) {
        $controllerPath = $basePath . "/app/Http/Controllers/{$controller}";
        
        if (!file_exists($controllerPath)) {
            continue;
        }

        $content = file_get_contents($controllerPath);
        
        // Verificar se já está usando BaseController
        if (strpos($content, 'extends BaseController') === false) {
            // Atualizar para usar BaseController
            $content = str_replace(
                'extends Controller',
                'extends BaseController',
                $content
            );
            
            $content = str_replace(
                'use App\Http\Controllers\Controller;',
                'use App\Http\Controllers\BaseController;',
                $content
            );
        }
        
        // Padronizar método index se existir
        $content = standardizeIndexMethod($content, $controller);
        
        file_put_contents($controllerPath, $content);
        echo "    ✅ {$controller}: Padronizado\n";
    }
}

function consolidateQuizControllers($controllers, $basePath) {
    echo "  📝 Consolidando controllers de quiz...\n";
    
    foreach ($controllers as $controller) {
        $controllerPath = $basePath . "/app/Http/Controllers/{$controller}";
        
        if (!file_exists($controllerPath)) {
            continue;
        }

        $content = file_get_contents($controllerPath);
        
        // Padronizar usando BaseController
        if (strpos($content, 'extends BaseController') === false) {
            $content = str_replace(
                'extends Controller',
                'extends BaseController',
                $content
            );
            
            $content = str_replace(
                'use App\Http\Controllers\Controller;',
                'use App\Http\Controllers\BaseController;',
                $content
            );
        }
        
        file_put_contents($controllerPath, $content);
        echo "    ✅ {$controller}: Padronizado\n";
    }
}

function createStandardViewController($controllerName) {
    $className = str_replace('.php', '', $controllerName);
    $viewName = strtolower(str_replace('Controller', '', $className));
    
    return "<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class {$className} extends BaseController
{
    /**
     * Display the main view
     */
    public function index()
    {
        return view('{$viewName}.index');
    }
}
";
}

function standardizeIndexMethod($content, $controllerName) {
    // Se o controller tem um método index muito simples, padronizar
    $pattern = '/public function index\([^}]*\)\s*\{[^}]*return view\([^}]*\);[^}]*\}/s';
    
    if (preg_match($pattern, $content)) {
        $viewName = strtolower(str_replace(['Controller.php', 'Controller'], '', $controllerName));
        
        $newIndexMethod = "public function index()
    {
        return view('{$viewName}.index');
    }";
        
        $content = preg_replace($pattern, $newIndexMethod, $content);
    }
    
    return $content;
}

function enhanceBaseController($basePath) {
    echo "  📝 Melhorando BaseController...\n";
    
    $baseControllerPath = $basePath . '/app/Http/Controllers/BaseController.php';
    $content = file_get_contents($baseControllerPath);
    
    // Adicionar método para views simples se não existir
    if (strpos($content, 'protected function simpleView') === false) {
        $simpleViewMethod = '
    /**
     * Retorna uma view simples
     */
    protected function simpleView(string $view, array $data = [])
    {
        return view($view, $data);
    }

    /**
     * Retorna uma view com dados do usuário autenticado
     */
    protected function viewWithUser(string $view, array $data = [])
    {
        $data[\'user\'] = auth()->user();
        return view($view, $data);
    }

    /**
     * Método padrão para controllers de dashboard
     */
    protected function dashboardView(string $view, array $data = [])
    {
        // Adicionar dados comuns de dashboard
        $data[\'notifications_count\'] = auth()->check() ? 
            auth()->user()->notifications()->unread()->count() : 0;
            
        return view($view, $data);
    }';

        // Inserir antes do último }
        $content = substr_replace($content, $simpleViewMethod . "\n", strrpos($content, '}'), 0);
        
        file_put_contents($baseControllerPath, $content);
        echo "    ✅ BaseController: Métodos de view adicionados\n";
    }
}