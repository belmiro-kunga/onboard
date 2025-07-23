<?php

echo "🔍 ANÁLISE PROFUNDA: MÓDULO DE CURSOS - ERROS 404\n\n";

// Verificar estrutura de arquivos
$requiredFiles = [
    // Controllers
    'app/Http/Controllers/Admin/CourseController.php',
    'app/Http/Controllers/Admin/CourseModuleController.php',
    'app/Http/Controllers/Admin/LessonController.php',
    'app/Http/Controllers/CourseController.php',
    
    // Models
    'app/Models/Course.php',
    'app/Models/Module.php',
    'app/Models/Lesson.php',
    'app/Models/LessonVideo.php',
    
    // Views Admin
    'resources/views/admin/courses/index.blade.php',
    'resources/views/admin/courses/show.blade.php',
    'resources/views/admin/courses/create.blade.php',
    'resources/views/admin/courses/edit.blade.php',
    'resources/views/admin/courses/modules/index.blade.php',
    'resources/views/admin/courses/modules/show.blade.php',
    'resources/views/admin/courses/modules/create.blade.php',
    'resources/views/admin/courses/modules/edit.blade.php',
    
    // Views Public
    'resources/views/courses/index.blade.php',
    'resources/views/courses/show.blade.php',
    'resources/views/lessons/show.blade.php',
    
    // Requests
    'app/Http/Requests/CourseRequest.php',
    'app/Http/Requests/ModuleRequest.php',
    'app/Http/Requests/LessonRequest.php',
];

echo "📁 VERIFICANDO ARQUIVOS NECESSÁRIOS:\n";
echo str_repeat("=", 60) . "\n";

$missingFiles = [];
$existingFiles = [];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ {$file}\n";
        $existingFiles[] = $file;
    } else {
        echo "❌ {$file} - ARQUIVO FALTANDO\n";
        $missingFiles[] = $file;
    }
}

echo "\n📊 RESUMO DE ARQUIVOS:\n";
echo "✅ Existentes: " . count($existingFiles) . "\n";
echo "❌ Faltando: " . count($missingFiles) . "\n";

// Verificar rotas no arquivo web.php
echo "\n🛣️ VERIFICANDO ROTAS:\n";
echo str_repeat("=", 60) . "\n";

if (file_exists('routes/web.php')) {
    $routesContent = file_get_contents('routes/web.php');
    
    $expectedRoutes = [
        // Rotas públicas
        "Route::prefix('courses')" => 'Rotas públicas de cursos',
        "Route::get('/', [App\\Http\\Controllers\\CourseController::class, 'index'])" => 'Listagem pública de cursos',
        "Route::get('/{course}', [App\\Http\\Controllers\\CourseController::class, 'show'])" => 'Visualização pública de curso',
        
        // Rotas admin - cursos
        "Route::prefix('courses')->name('courses.')" => 'Grupo de rotas admin de cursos',
        "Route::get('/', [App\\Http\\Controllers\\Admin\\CourseController::class, 'index'])" => 'Admin - listagem de cursos',
        "Route::get('/create', [App\\Http\\Controllers\\Admin\\CourseController::class, 'create'])" => 'Admin - criar curso',
        "Route::post('/', [App\\Http\\Controllers\\Admin\\CourseController::class, 'store'])" => 'Admin - salvar curso',
        
        // Rotas admin - módulos
        "Route::prefix('/{course}/modules')->name('modules.')" => 'Grupo de rotas de módulos por curso',
        "CourseModuleController" => 'Controller de módulos por curso',
        
        // Rotas de aulas
        "Route::prefix('lessons')->name('lessons.')" => 'Grupo de rotas de aulas',
        "LessonController" => 'Controller de aulas',
    ];
    
    foreach ($expectedRoutes as $route => $description) {
        if (strpos($routesContent, $route) !== false) {
            echo "✅ {$description}\n";
        } else {
            echo "❌ {$description} - ROTA FALTANDO\n";
        }
    }
} else {
    echo "❌ Arquivo routes/web.php não encontrado!\n";
}

// Verificar URLs específicas que podem dar 404
echo "\n🔗 URLS PROBLEMÁTICAS IDENTIFICADAS:\n";
echo str_repeat("=", 60) . "\n";

$problematicUrls = [
    'http://127.0.0.1:8000/admin/15/modules' => [
        'problema' => 'URL incorreta - falta "courses" no caminho',
        'correto' => 'http://127.0.0.1:8000/admin/courses/15/modules',
        'causa' => 'Rota mal configurada ou link incorreto'
    ],
    'http://127.0.0.1:8000/admin/courses/{id}/modules/create' => [
        'problema' => 'Pode não existir se CourseModuleController não estiver registrado',
        'correto' => 'Verificar se rota está registrada corretamente',
        'causa' => 'Controller ou rota não registrada'
    ],
    'http://127.0.0.1:8000/admin/lessons/{id}' => [
        'problema' => 'Pode não existir se LessonController não estiver registrado',
        'correto' => 'Verificar rotas de aulas',
        'causa' => 'Sistema de aulas pode não estar totalmente implementado'
    ],
];

foreach ($problematicUrls as $url => $info) {
    echo "🔴 URL: {$url}\n";
    echo "   Problema: {$info['problema']}\n";
    echo "   Correção: {$info['correto']}\n";
    echo "   Causa: {$info['causa']}\n\n";
}

// Verificar controllers específicos
echo "🎮 VERIFICANDO CONTROLLERS:\n";
echo str_repeat("=", 60) . "\n";

$controllers = [
    'app/Http/Controllers/Admin/CourseController.php' => [
        'methods' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
        'description' => 'CRUD básico de cursos'
    ],
    'app/Http/Controllers/Admin/CourseModuleController.php' => [
        'methods' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
        'description' => 'Gestão de módulos por curso'
    ],
    'app/Http/Controllers/Admin/LessonController.php' => [
        'methods' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'],
        'description' => 'Gestão de aulas'
    ],
];

foreach ($controllers as $controllerPath => $info) {
    if (file_exists($controllerPath)) {
        echo "✅ {$controllerPath}\n";
        
        $content = file_get_contents($controllerPath);
        $missingMethods = [];
        
        foreach ($info['methods'] as $method) {
            if (strpos($content, "public function {$method}(") === false) {
                $missingMethods[] = $method;
            }
        }
        
        if (empty($missingMethods)) {
            echo "   ✅ Todos os métodos implementados\n";
        } else {
            echo "   ⚠️  Métodos faltando: " . implode(', ', $missingMethods) . "\n";
        }
    } else {
        echo "❌ {$controllerPath} - CONTROLLER FALTANDO\n";
    }
    echo "\n";
}

// Verificar views críticas
echo "👁️ VERIFICANDO VIEWS CRÍTICAS:\n";
echo str_repeat("=", 60) . "\n";

$criticalViews = [
    'resources/views/admin/courses/modules/index.blade.php' => 'Listagem de módulos por curso',
    'resources/views/admin/courses/modules/create.blade.php' => 'Criar módulo',
    'resources/views/admin/courses/modules/edit.blade.php' => 'Editar módulo',
    'resources/views/admin/courses/modules/show.blade.php' => 'Visualizar módulo',
    'resources/views/layouts/admin.blade.php' => 'Layout administrativo',
];

foreach ($criticalViews as $viewPath => $description) {
    if (file_exists($viewPath)) {
        echo "✅ {$viewPath} - {$description}\n";
    } else {
        echo "❌ {$viewPath} - {$description} - VIEW FALTANDO\n";
    }
}

// Gerar relatório de correções necessárias
echo "\n🔧 CORREÇÕES NECESSÁRIAS:\n";
echo str_repeat("=", 60) . "\n";

$corrections = [
    'ALTA PRIORIDADE' => [
        'Corrigir URL /admin/15/modules para /admin/courses/15/modules',
        'Verificar se CourseModuleController está registrado nas rotas',
        'Criar views faltando para módulos',
        'Verificar se layout admin existe e funciona',
    ],
    'MÉDIA PRIORIDADE' => [
        'Implementar métodos faltando nos controllers',
        'Criar Form Requests para validação',
        'Verificar relacionamentos entre models',
        'Testar todas as rotas CRUD',
    ],
    'BAIXA PRIORIDADE' => [
        'Otimizar queries dos controllers',
        'Adicionar middleware de autorização',
        'Implementar cache onde necessário',
        'Melhorar tratamento de erros',
    ]
];

foreach ($corrections as $priority => $items) {
    echo "\n🎯 {$priority}:\n";
    foreach ($items as $item) {
        echo "   • {$item}\n";
    }
}

// URLs para testar
echo "\n🧪 URLS PARA TESTAR:\n";
echo str_repeat("=", 60) . "\n";

$testUrls = [
    'Login Admin' => 'http://127.0.0.1:8000/admin/login',
    'Dashboard Admin' => 'http://127.0.0.1:8000/admin/dashboard',
    'Cursos Admin' => 'http://127.0.0.1:8000/admin/courses',
    'Criar Curso' => 'http://127.0.0.1:8000/admin/courses/create',
    'Módulos do Curso' => 'http://127.0.0.1:8000/admin/courses/1/modules',
    'Criar Módulo' => 'http://127.0.0.1:8000/admin/courses/1/modules/create',
    'Cursos Públicos' => 'http://127.0.0.1:8000/courses',
];

foreach ($testUrls as $name => $url) {
    echo "🔗 {$name}: {$url}\n";
}

echo "\n✅ ANÁLISE CONCLUÍDA!\n";
echo "Execute os testes nas URLs acima para identificar erros 404 específicos.\n";
echo "Priorize as correções de ALTA PRIORIDADE primeiro.\n";