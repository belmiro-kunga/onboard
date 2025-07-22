<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hcp:create-admin {email?} {name?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um usuário administrador para o sistema HCP';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Qual o email do administrador?', 'admin@hcp.com');
        $name = $this->argument('name') ?? $this->ask('Qual o nome do administrador?', 'Administrador HCP');
        $password = $this->argument('password') ?? $this->secret('Qual a senha do administrador?') ?? 'password';

        // Verificar se o usuário já existe
        $existingUser = User::where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser->role === 'admin') {
                $this->info("Usuário administrador com email {$email} já existe!");
                
                if ($this->confirm('Deseja atualizar a senha deste usuário?', false)) {
                    $existingUser->password = Hash::make($password);
                    $existingUser->save();
                    $this->info('Senha atualizada com sucesso!');
                }
                
                return;
            }
            
            if ($this->confirm("Usuário com email {$email} já existe, mas não é administrador. Deseja promovê-lo a administrador?", true)) {
                $existingUser->role = 'admin';
                $existingUser->save();
                $this->info("Usuário {$email} promovido a administrador com sucesso!");
                return;
            }
            
            $this->error("Usuário com email {$email} já existe e não foi promovido a administrador.");
            return;
        }

        // Criar novo usuário administrador
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("Usuário administrador criado com sucesso!");
        $this->table(
            ['Nome', 'Email', 'Role'],
            [[$user->name, $user->email, $user->role]]
        );
    }
}