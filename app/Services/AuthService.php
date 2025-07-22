<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Buscar usuário por email
     */
    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Verificar credenciais do usuário
     */
    public function checkCredentials(array $credentials): bool
    {
        $user = $this->findUserByEmail($credentials['email']);
        
        if (!$user) {
            return false;
        }

        return Hash::check($credentials['password'], $user->password);
    }

    /**
     * Fazer login do usuário
     */
    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    /**
     * Fazer logout do usuário
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Verificar se usuário está autenticado
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Verificar se usuário é admin
     */
    public function isAdmin(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Verificar se usuário é manager
     */
    public function isManager(): bool
    {
        return Auth::check() && Auth::user()->role === 'manager';
    }

    /**
     * Obter usuário autenticado
     */
    public function user(): ?User
    {
        return Auth::user();
    }

    /**
     * Obter ID do usuário autenticado
     */
    public function userId(): ?int
    {
        return Auth::id();
    }

    /**
     * Verificar se usuário está ativo
     */
    public function isActiveUser(): bool
    {
        return Auth::check() && Auth::user()->is_active;
    }

    /**
     * Contar admins
     */
    public function countAdmins(): int
    {
        return User::where('role', 'admin')->count();
    }

    /**
     * Contar usuários ativos
     */
    public function countActiveUsers(): int
    {
        return User::where('is_active', true)->count();
    }

    /**
     * Buscar usuários por role
     */
    public function getUsersByRole(string $role)
    {
        return User::where('role', $role)->get();
    }

    /**
     * Verificar se precisa redirecionar para login
     */
    public function needsLogin(): bool
    {
        return !$this->isAuthenticated();
    }

    /**
     * Verificar se precisa redirecionar para admin
     */
    public function needsAdmin(): bool
    {
        return !$this->isAdmin();
    }

    /**
     * Gerar token de reset de senha
     */
    public function generateResetToken(string $email): ?string
    {
        $user = $this->findUserByEmail($email);
        
        if (!$user) {
            return null;
        }

        // Lógica para gerar token
        return \Illuminate\Support\Str::random(60);
    }

    /**
     * Resetar senha do usuário
     */
    public function resetPassword(string $email, string $password, string $token): bool
    {
        $user = $this->findUserByEmail($email);
        
        if (!$user) {
            return false;
        }

        // Verificar token (implementar lógica de verificação)
        
        $user->update([
            'password' => Hash::make($password)
        ]);

        return true;
    }
}
