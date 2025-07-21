<?php

/**
 * Script para acessar o painel administrativo do HCP
 * 
 * Este script verifica se existe um usuário administrador e, se não existir,
 * cria um novo usuário com a role 'admin'.
 */

// Carregar o ambiente Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== Verificando acesso ao painel administrativo HCP ===\n\n";

// Verificar se existe algum usuário admin
$adminUser = User::where('role', 'admin')->first();

if ($adminUser) {
    echo "✅ Usuário administrador encontrado:\n";
    echo "   Nome: {$adminUser->name}\n";
    echo "   Email: {$adminUser->email}\n\n";
    
    echo "Para acessar o painel administrativo:\n";
    echo "1. Acesse a URL: /admin\n";
    echo "2. Faça login com as credenciais do administrador\n";
    echo "3. Você será redirecionado para o painel administrativo\n\n";
} else {
    echo "❌ Nenhum usuário administrador encontrado.\n\n";
    
    echo "Criando um novo usuário administrador...\n";
    
    // Criar um novo usuário admin
    $name = 'Administrador HCP';
    $email = 'admin@hcp.com';
    $password = 'admin123';
    
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    
    echo "✅ Usuário administrador criado com sucesso!\n";
    echo "   Nome: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Senha: {$password}\n\n";
    
    echo "Para acessar o painel administrativo:\n";
    echo "1. Acesse a URL: /admin\n";
    echo "2. Faça login com as credenciais acima\n";
    echo "3. Você será redirecionado para o painel administrativo\n\n";
}

echo "=== Verificação concluída ===\n";