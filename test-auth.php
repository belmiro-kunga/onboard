<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Testar conexão com o banco de dados
try {
    DB::connection()->getPdo();
    echo "✅ Conexão com o banco de dados estabelecida com sucesso!\n";
} catch (\Exception $e) {
    die("❌ Não foi possível conectar ao banco de dados: " . $e->getMessage() . "\n");
}

// Verificar tabela de usuários
try {
    $usersCount = DB::table('users')->count();
    echo "✅ Tabela de usuários encontrada com $usersCount registros.\n";
} catch (\Exception $e) {
    die("❌ Erro ao acessar a tabela de usuários: " . $e->getMessage() . "\n");
}

// Listar usuários
try {
    $users = DB::table('users')->select('id', 'name', 'email', 'is_active', 'role')->get();
    echo "\n📋 Usuários no banco de dados:\n";
    foreach ($users as $user) {
        echo "- ID: {$user->id}, Nome: {$user->name}, Email: {$user->email}, Ativo: " . ($user->is_active ? 'Sim' : 'Não') . ", Função: {$user->role}\n";
    }
} catch (\Exception $e) {
    echo "⚠️ Não foi possível listar os usuários: " . $e->getMessage() . "\n";
}

// Testar autenticação com o usuário admin
$email = 'admin@hemera.com';
$password = '123456';

try {
    $user = DB::table('users')->where('email', $email)->first();
    
    if (!$user) {
        die("\n❌ Usuário $email não encontrado.\n");
    }
    
    echo "\n🔍 Usuário encontrado: {$user->name} ({$user->email})\n";
    echo "📝 Status da conta: " . ($user->is_active ? 'Ativa' : 'Inativa') . "\n";
    
    // Verificar senha
    if (Hash::check($password, $user->password)) {
        echo "✅ Senha correta!\n";
        
        // Tentar autenticar
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            echo "✅ Autenticação bem-sucedida!\n";
            echo "🔑 ID da sessão: " . session()->getId() . "\n";
        } else {
            echo "❌ Falha na autenticação. Motivo: " . (Auth::check() ? 'Desconhecido' : 'Credenciais inválidas') . "\n";
        }
    } else {
        echo "❌ Senha incorreta!\n";
    }
    
} catch (\Exception $e) {
    echo "\n❌ Erro durante a autenticação: " . $e->getMessage() . "\n";
}

echo "\n✅ Script concluído.\n";
