<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Criar usuário de teste se não existir
        $testUser = User::where('email', 'funcionario@hcp.com')->first();
        
        if (!$testUser) {
            User::create([
                'name' => 'João Silva',
                'email' => 'funcionario@hcp.com',
                'password' => Hash::make('123456'),
                'department' => 'TI',
                'role' => 'employee',
                'is_active' => true,
                'position' => 'Desenvolvedor',
                'hire_date' => now(),
            ]);
            
            $this->command->info('✅ Usuário funcionário criado: funcionario@hcp.com / 123456');
        }
        
        // Criar usuário gestor se não existir
        $managerUser = User::where('email', 'gestor@hcp.com')->first();
        
        if (!$managerUser) {
            User::create([
                'name' => 'Maria Santos',
                'email' => 'gestor@hcp.com',
                'password' => Hash::make('123456'),
                'department' => 'RH',
                'role' => 'manager',
                'is_active' => true,
                'position' => 'Gerente de RH',
                'hire_date' => now()->subYear(),
            ]);
            
            $this->command->info('✅ Usuário gestor criado: gestor@hcp.com / 123456');
        }
        
        // Criar usuário admin se não existir
        $adminUser = User::where('email', 'admin@hcp.com')->first();
        
        if (!$adminUser) {
            User::create([
                'name' => 'Carlos Admin',
                'email' => 'admin@hcp.com',
                'password' => Hash::make('123456'),
                'department' => 'TI',
                'role' => 'admin',
                'is_active' => true,
                'position' => 'Administrador do Sistema',
                'hire_date' => now()->subYears(2),
            ]);
            
            $this->command->info('✅ Usuário admin criado: admin@hcp.com / 123456');
        }
        
        $this->command->info('🎯 Usuários de teste prontos para login!');
        $this->command->info('');
        $this->command->info('📋 Credenciais de teste:');
        $this->command->info('   Funcionário: funcionario@hcp.com / 123456');
        $this->command->info('   Gestor: gestor@hcp.com / 123456');
        $this->command->info('   Admin: admin@hcp.com / 123456');
    }
}