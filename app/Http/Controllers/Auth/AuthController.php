<?php

namespace App\Http\Controllers\Auth;


use App\Services\AuthService;use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Exibir formulário de login
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Processar login do usuário
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        \Log::info('Tentativa de login', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $credentials = $request->validated();
        
        // Verificar se o usuário existe e está ativo
        $user = $this->authService->findUserByEmail(credentials['email']);
        
        if (!$user) {
            \Log::warning('Usuário não encontrado', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Credenciais inválidas.',
            ])->onlyInput('email');
        }
        
        if (!$user->is_active) {
            \Log::warning('Tentativa de login em conta desativada', ['user_id' => $user->id]);
            return back()->withErrors([
                'email' => 'Sua conta foi desativada. Entre em contato com o administrador.',
            ])->onlyInput('email');
        }

        // Verificar manualmente a senha para depuração
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            \Log::warning('Senha incorreta para o usuário', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return back()->withErrors([
                'email' => 'Credenciais inválidas.',
            ])->onlyInput('email');
        }

        \Log::info('Credenciais válidas, tentando autenticar', ['user_id' => $user->id]);
        
        // Tentar fazer login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Mensagem personalizada de boas-vindas
            $greeting = $this->getGreeting();
            $message = "{$greeting}, {$user->name}! Pronto para mais uma etapa?";
            
            // Redirecionar baseado no papel do usuário
            $redirectTo = $this->getRedirectPath($user);
            
            \Log::info('Login bem-sucedido', [
                'user_id' => $user->id,
                'redirect_to' => $redirectTo
            ]);
            
            return redirect()->intended($redirectTo)
                ->with('success', $message);
        }

        \Log::warning('Falha na autenticação', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return back()->withErrors([
            'email' => 'Credenciais inválidas.',
        ])->onlyInput('email');
    }

    /**
     * Fazer logout do usuário
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')
            ->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Exibir formulário de esqueci minha senha
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar link de recuperação de senha
     */
    public function sendResetLink(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link de recuperação enviado para seu e-mail!');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    /**
     * Exibir formulário de redefinir senha
     */
    public function showResetPassword(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Processar redefinição de senha
     */
    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/')
                ->with('success', 'Senha redefinida com sucesso!');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Obter saudação baseada no horário
     */
    private function getGreeting(): string
    {
        $hour = now()->hour;
        
        if ($hour < 12) {
            return 'Bom dia';
        } elseif ($hour < 18) {
            return 'Boa tarde';
        } else {
            return 'Boa noite';
        }
    }

    /**
     * Obter caminho de redirecionamento baseado no papel do usuário
     */
    private function getRedirectPath(User $user): string
    {
        // Verificar papel baseado no campo 'role' do usuário
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role === 'manager') {
            return route('dashboard'); // Managers usam o dashboard normal por enquanto
        } else {
            return route('dashboard');
        }
    }
}