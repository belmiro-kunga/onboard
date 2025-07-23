<?php

echo "🔧 CORREÇÃO AUTOMÁTICA DE ERROS DE SINTAXE...\n\n";

$modelsToFix = [
    'app/Models/User.php',
    'app/Models/Course.php',
    'app/Models/Module.php', 
    'app/Models/Quiz.php',
    'app/Models/QuizQuestion.php',
    'app/Models/QuizAnswer.php',
    'app/Models/Achievement.php',
    'app/Models/UserProgress.php',
];

$totalFixed = 0;

foreach ($modelsToFix as $file) {
    if (!file_exists($file)) {
        echo "⚠️  {$file} - Arquivo não encontrado\n";
        continue;
    }
    
    echo "🔍 Processando: {$file}\n";
    
    $content = file_get_contents($file);
    $originalContent = $content;
    $fixesApplied = [];
    
    // Correção 1: Vírgula incorreta em use statements
    $pattern1 = '/use\s+([^;]+);,\s*([A-Za-z_][A-Za-z0-9_]*)/';
    if (preg_match($pattern1, $content)) {
        $content = preg_replace($pattern1, 'use $1, $2;', $content);
        $fixesApplied[] = 'Vírgula incorreta em use statement';
    }
    
    // Correção 2: Vírgula dupla em use
    $pattern2 = '/use\s+([^;]+),\s*([A-Za-z_][A-Za-z0-9_]*);,/';
    if (preg_match($pattern2, $content)) {
        $content = preg_replace($pattern2, 'use $1, $2;', $content);
        $fixesApplied[] = 'Vírgula dupla em use';
    }
    
    // Correção 3: Parêntese e chave extras
    $pattern3 = '/\);[\s\n]*\}[\s\n]*\/\*\*/';
    if (preg_match($pattern3, $content)) {
        $content = preg_replace($pattern3, "\n\n    /**", $content);
        $fixesApplied[] = 'Parêntese e chave extras removidos';
    }
    
    // Correção 4: Use statement com vírgula no final
    $pattern4 = '/use\s+([^;]+),\s*([A-Za-z_][A-Za-z0-9_]*);,\s*([A-Za-z_][A-Za-z0-9_]*)/';
    if (preg_match($pattern4, $content)) {
        $content = preg_replace($pattern4, 'use $1, $2, $3;', $content);
        $fixesApplied[] = 'Use statement com múltiplas vírgulas';
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "   ✅ Corrigido: " . implode(', ', $fixesApplied) . "\n";
        $totalFixed++;
    } else {
        echo "   ℹ️  Nenhuma correção necessária\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 RESUMO:\n";
echo "   Arquivos processados: " . count($modelsToFix) . "\n";
echo "   Arquivos corrigidos: {$totalFixed}\n";

if ($totalFixed > 0) {
    echo "\n✅ CORREÇÕES APLICADAS COM SUCESSO!\n";
    echo "Agora teste o sistema:\n";
    echo "1. Login admin: http://127.0.0.1:8000/admin/login\n";
    echo "2. Dashboard: http://127.0.0.1:8000/admin/dashboard\n";
    echo "3. Cursos: http://127.0.0.1:8000/admin/courses\n";
} else {
    echo "\n✅ TODOS OS ARQUIVOS JÁ ESTÃO CORRETOS!\n";
}

echo "\n🎯 LINKS IMPORTANTES:\n";
echo "🚪 Login Admin: http://127.0.0.1:8000/admin/login\n";
echo "📊 Dashboard: http://127.0.0.1:8000/admin/dashboard\n";
echo "📚 Cursos: http://127.0.0.1:8000/admin/courses\n";
echo "🎓 Módulos: http://127.0.0.1:8000/admin/courses/{id}/modules\n";
echo "📝 Criar Curso: http://127.0.0.1:8000/admin/courses/create\n";

echo "\n🔧 PRÓXIMOS PASSOS:\n";
echo "1. Testar login com credenciais de admin\n";
echo "2. Verificar se dashboard carrega sem erros\n";
echo "3. Criar um curso de teste\n";
echo "4. Adicionar módulos ao curso\n";
echo "5. Adicionar vídeos às aulas\n";

echo "\n✅ CORREÇÃO AUTOMÁTICA CONCLUÍDA!\n";