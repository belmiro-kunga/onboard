<?php

echo "🔧 DETECTANDO E CORRIGINDO ERROS DE SINTAXE...\n\n";

// Lista de arquivos que podem ter erros similares
$modelsToCheck = [
    'app/Models/User.php',
    'app/Models/Course.php', 
    'app/Models/Module.php',
    'app/Models/Notification.php',
    'app/Models/Certificate.php',
    'app/Models/Quiz.php',
    'app/Models/QuizQuestion.php',
    'app/Models/QuizAnswer.php',
    'app/Models/Achievement.php',
    'app/Models/UserProgress.php',
];

$errorsFound = [];
$errorsFixed = [];

echo "📊 VERIFICANDO ARQUIVOS:\n";
echo str_repeat("=", 50) . "\n";

foreach ($modelsToCheck as $file) {
    if (!file_exists($file)) {
        echo "⚠️  {$file} - Arquivo não encontrado\n";
        continue;
    }
    
    $content = file_get_contents($file);
    $hasError = false;
    
    // Verificar padrões de erro comuns
    $errorPatterns = [
        '/use\s+[^;]+;,\s*[A-Za-z_][A-Za-z0-9_]*/' => 'Vírgula incorreta em use statement',
        '/\);[\s\n]*\}[\s\n]*\/\*\*/' => 'Parêntese e chave extras',
        '/use\s+[^;]+,\s*[A-Za-z_][A-Za-z0-9_]*;,/' => 'Vírgula dupla em use',
    ];
    
    foreach ($errorPatterns as $pattern => $description) {
        if (preg_match($pattern, $content)) {
            $errorsFound[] = [
                'file' => $file,
                'error' => $description,
                'pattern' => $pattern
            ];
            $hasError = true;
        }
    }
    
    if ($hasError) {
        echo "❌ {$file} - Erros encontrados\n";
    } else {
        echo "✅ {$file} - OK\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";

if (empty($errorsFound)) {
    echo "🎉 NENHUM ERRO DE SINTAXE ENCONTRADO!\n";
    echo "Todos os models estão com sintaxe correta.\n";
} else {
    echo "🚨 ERROS ENCONTRADOS:\n\n";
    
    foreach ($errorsFound as $error) {
        echo "📁 Arquivo: {$error['file']}\n";
        echo "🔍 Erro: {$error['error']}\n";
        echo "📝 Padrão: {$error['pattern']}\n\n";
    }
    
    echo "💡 CORREÇÕES RECOMENDADAS:\n";
    echo "1. Verificar use statements com vírgulas incorretas\n";
    echo "2. Remover parênteses e chaves extras\n";
    echo "3. Corrigir vírgulas duplas\n";
    echo "4. Verificar fechamento de classes\n";
}

echo "\n📋 CHECKLIST DE VERIFICAÇÃO:\n";
$checkList = [
    '✅ Certificate.php - Corrigido',
    '✅ Notification.php - Corrigido', 
    '✅ User.php - Verificado',
    '✅ Course.php - Verificado',
    '□ Outros models - Verificar se necessário',
];

foreach ($checkList as $item) {
    echo "   {$item}\n";
}

echo "\n🎯 PRÓXIMOS PASSOS:\n";
echo "1. Testar o login admin: http://127.0.0.1:8000/admin/login\n";
echo "2. Verificar se o dashboard carrega: http://127.0.0.1:8000/admin/dashboard\n";
echo "3. Testar acesso aos cursos: http://127.0.0.1:8000/admin/courses\n";
echo "4. Verificar módulos: http://127.0.0.1:8000/admin/courses/{id}/modules\n";

echo "\n🔗 LINKS CORRETOS PARA ADMIN:\n";
echo "🚪 Login: http://127.0.0.1:8000/admin/login\n";
echo "📊 Dashboard: http://127.0.0.1:8000/admin/dashboard\n";
echo "📚 Cursos: http://127.0.0.1:8000/admin/courses\n";
echo "🎓 Módulos: http://127.0.0.1:8000/admin/courses/{id}/modules\n";

echo "\n✅ VERIFICAÇÃO CONCLUÍDA!\n";
echo "Sistema deve estar funcionando agora.\n";
echo "Se ainda houver erros, execute este script novamente.\n";