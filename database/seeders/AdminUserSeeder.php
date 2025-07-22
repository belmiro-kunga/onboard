<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
        if (!User::where('email', 'admin@hcp.com')->exists()) {
            User::create([
                'name' => 'Administrador HCP',
                'email' => 'admin@hcp.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'department' => 'TI',
                'position' => 'Administrador do Sistema',
                'phone' => '(11) 99999-9999',
                'hire_date' => now()->subYears(2),
                'last_login_at' => now(),
            ]);

            $this->command->info('✅ Usuário admin criado com sucesso!');
            $this->command->info('📧 Email: admin@hcp.com');
            $this->command->info('🔑 Senha: admin123');
        } else {
            $this->command->info('ℹ️ Usuário admin já existe.');
        }
    }
}