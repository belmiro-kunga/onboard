<?php

/**
 * FASE 3.3: Centralizar Lógica de Autenticação
 * Cria AuthService e centraliza queries e redirects de autenticação
 */

echo "🚀 FASE 3.3: CENTRALIZANDO AUTENTICAÇÃO...\n\n";

$basePath = dirname(__DIR__);

// Criar AuthService
createAuthService($basePath);

// Criar AuthMiddleware melhorado
createAuthMiddleware($basePath);

// Atualizar controllers para usar AuthService
updateControllersWithAuthService($basePath);

echo "\n✅ FASE 3.3 concluída! Autenticação centralizada.\n";

function createAuthService($basePath) {
    echo "📊 Criando AuthService...\n";
    
    $servicePath = $basePath . '/app/Services';
    
    $authServiceContent = '<?php

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
        return User::where(\'email\', $email)->first();
    }

    /**
     * Verificar credenciais do usuário
     */
    public function checkCredentials(array $credentials): bool
    {
        $user = $this->findUserByEmail($credentials[\'email\']);
        
        if (!$user) {
            return false;
        }

        return Hash::check($credentials[\'password\'], $user->password);
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
        return Auth::check() && Auth::user()->role === \'admin\';
    }

    /**
     * Verificar se usuário é manager
     */
    public function isManager(): bool
    {
        return Auth::check() && Auth::user()->role === \'manager\';
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
        return User::where(\'role\', \'admin\')->count();
    }

    /**
     * Contar usuários ativos
     */
    public function countActiveUsers(): int
    {
        return User::where(\'is_active\', true)->count();
    }

    /**
     * Buscar usuários por role
     */
    public function getUsersByRole(string $role)
    {
        return User::where(\'role\', $role)->get();
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
            \'password\' => Hash::make($password)
        ]);

        return true;
    }
}
';

    file_put_contents($servicePath . '/AuthService.php', $authServiceContent);
    echo "  ✅ AuthService criado\n";
}

function createAuthMiddleware($basePath) {
    echo "📊 Criando AuthMiddleware melhorado...\n";
    
    $middlewarePath = $basePath . '/app/Http/Middleware';
    
    $authMiddlewareContent = '<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Responses\WebResponse;

class EnhancedAuthMiddleware
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {
        if ($this->authService->needsLogin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    \'success\' => false,
                    \'message\' => \'Usuário não autenticado\'
                ], 401);
            }

            return WebResponse::redirectToLogin(\'Você precisa estar logado para acessar esta página\');
        }

        if (!$this->authService->isActiveUser()) {
            $this->authService->logout();
            
            return WebResponse::redirectToLogin(\'Sua conta foi desativada. Entre em contato com o administrador.\');
        }

        return $next($request);
    }
}
';

    file_put_contents($middlewarePath . '/EnhancedAuthMiddleware.php', $authMiddlewareContent);
    echo "  ✅ EnhancedAuthMiddleware criado\n";
}

function updateControllersWithAuthService($basePath) {
    echo "📊 Atualizando controllers para usar AuthService...\n";
    
    // Controllers que fazem queries de autenticação
    $controllersToUpdate = [
        'Auth/AdminAuthController.php',
        'Auth/AuthController.php',
        'Admin/UserDashboardController.php',
        'GamificationController.php',
        'ProgressController.php'
    ];

    foreach ($controllersToUpdate as $controller) {
        updateControllerWithAuthService($controller, $basePath);
    }
}

function updateControllerWithAuthService($controllerPath, $basePath) {
    $fullPath = $basePath . "/app/Http/Controllers/{$controllerPath}";
    
    if (!file_exists($fullPath)) {
        return;
    }

    $content = file_get_contents($fullPath);
    $originalContent = $content;

    // Adicionar import do AuthService se não existir
    if (strpos($content, 'use App\\Services\\AuthService;') === false) {
        $content = preg_replace(
            '/(namespace[^;]+;)(\s*\n)/',
            '$1$2' . "\nuse App\\Services\\AuthService;",
            $content,
            1
        );
    }

    // Substituir queries comuns por métodos do AuthService
    $replacements = [
        // User::where('email', $email)->first()
        '/User::where\(\'email\', \$([^)]+)\)->first\(\)/' => '$this->authService->findUserByEmail($1)',
        
        // User::where('role', 'admin')->count()
        '/User::where\(\'role\', \'admin\'\)->count\(\)/' => '$this->authService->countAdmins()',
        
        // Auth::check()
        '/Auth::check\(\)/' => '$this->authService->isAuthenticated()',
        
        // Auth::user()
        '/Auth::user\(\)/' => '$this->authService->user()',
        
        // Auth::id()
        '/Auth::id\(\)/' => '$this->authService->userId()'
    ];

    foreach ($replacements as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }

    // Substituir redirects para login
    $content = preg_replace(
        '/return redirect\(\)->route\(\'login\'\)/',
        'return \\App\\Http\\Responses\\WebResponse::redirectToLogin()',
        $content
    );

    if ($content !== $originalContent) {
        file_put_contents($fullPath, $content);
        echo "  ✅ {$controllerPath}: AuthService aplicado\n";
    }
}