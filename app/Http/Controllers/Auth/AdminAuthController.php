<?php

namespace App\Http\Controllers\Auth;


use App\Services\AuthService;use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Exibir formulário de login do admin
     */
    public function showLogin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Processar login do admin
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        \Log::info('Tentativa de login admin', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $credentials = $request->validated();
        
        // Verificar se o usuário existe e é admin
        $user = $this->authService->findUserByEmail($credentials['email']);
        
        if (!$user) {
            \Log::warning('Admin não encontrado', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Credenciais de administrador inválidas.',
            ])->onlyInput('email');
        }
        
        // Verificar se é admin
        if ($user->role !== 'admin') {
            \Log::warning('Tentativa de acesso admin por usuário não-admin', [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
            return back()->withErrors([
                'email' => 'Acesso negado. Apenas administradores podem acessar esta área.',
            ])->onlyInput('email');
        }
        
        if (!$user->is_active) {
            \Log::warning('Tentativa de login admin em conta desativada', ['user_id' => $user->id]);
            return back()->withErrors([
                'email' => 'Conta de administrador desativada. Entre em contato com o suporte.',
            ])->onlyInput('email');
        }

        // Verificar senha
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            \Log::warning('Senha incorreta para admin', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return back()->withErrors([
                'email' => 'Credenciais de administrador inválidas.',
            ])->onlyInput('email');
        }

        \Log::info('Credenciais de admin válidas, tentando autenticar', ['user_id' => $user->id]);
        
        // Tentar fazer login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Atualizar último login
            $user->update(['last_login_at' => now()]);
            
            \Log::info('Login de admin bem-sucedido', [
                'user_id' => $user->id,
                'redirect_to' => route('admin.dashboard')
            ]);
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', "Bem-vindo ao painel administrativo, {$user->name}!");
        }

        \Log::warning('Falha na autenticação de admin', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return back()->withErrors([
            'email' => 'Credenciais de administrador inválidas.',
        ])->onlyInput('email');
    }

    /**
     * Dashboard do admin
     */
    public function dashboard(): View
    {
        $stats = [
            'users_count' => \App\Models\User::count(),
            'modules_count' => \App\Models\Module::count(),
            'certificates_count' => \App\Models\Certificate::count(),
            'quizzes_count' => \App\Models\Quiz::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Fazer logout do admin
     */
    public function logout(Request $request): RedirectResponse
    {
        \Log::info('Logout de admin', ['user_id' => auth()->id()]);
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'Logout administrativo realizado com sucesso!');
    }
}