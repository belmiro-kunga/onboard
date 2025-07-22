<?php

namespace App\Http\Controllers\Admin;



use App\Repositories\UserRepository;use App\Services\AuthService;use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class UserDashboardController extends BaseAdminController
{
    /**
     * Exibir dashboard de gestão de usuários
     */
    public function index(): View
    {
        $stats = $this->getUserStatistics();
        
        return view('admin.users.dashboard', compact('stats'));
    }

    /**
     * Obter estatísticas dos usuários
     */
    private function getUserStatistics(): array
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $adminUsers = $this->authService->countAdmins();
        $managerUsers = User::where('role', 'manager')->count();
        $employeeUsers = User::where('role', 'employee')->count();
        $newUsers = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        // Calcular percentuais
        $adminPercentage = $totalUsers > 0 ? round(($adminUsers / $totalUsers) * 100, 1) : 0;
        $managerPercentage = $totalUsers > 0 ? round(($managerUsers / $totalUsers) * 100, 1) : 0;
        $employeePercentage = $totalUsers > 0 ? round(($employeeUsers / $totalUsers) * 100, 1) : 0;

        return [
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'admin_users' => $adminUsers,
            'manager_users' => $managerUsers,
            'employee_users' => $employeeUsers,
            'new_users' => $newUsers,
            'admin_percentage' => $adminPercentage,
            'manager_percentage' => $managerPercentage,
            'employee_percentage' => $employeePercentage,
            'inactive_users' => $totalUsers - $activeUsers,
        ];
    }

    /**
     * Obter dados para gráficos (API)
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', '30'); // dias
        
        $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'labels' => $data->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'data' => $data->pluck('count'),
        ]);
    }

    /**
     * Obter usuários por departamento
     */
    public function getUsersByDepartment()
    {
        $departments = User::selectRaw('department, COUNT(*) as count')
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json($departments);
    }

    /**
     * Obter atividade recente dos usuários
     */
    public function getRecentActivity()
    {
        $recentUsers = User::with(['modules', 'certificates'])
            ->orderBy('last_login_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&color=FFFFFF&background=6366F1",
                    'last_login' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca',
                    'modules_completed' => $user->modules()->wherePivot('completed', true)->count(),
                    'certificates_count' => $user->certificates()->count(),
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                ];
            });

        return response()->json($recentUsers);
    }

    /**
     * Exportar relatório de usuários
     */
    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'csv'); // csv, excel, pdf
        $filters = $request->only(['role', 'department', 'status', 'date_from', 'date_to']);
        
        $query = User::query();
        
        // Aplicar filtros
        if (!empty($filters['role'])) {
            $query->where('role', $filters['role']);
        }
        
        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }
        
        if (!empty($filters['status'])) {
            $isActive = $filters['status'] === 'active';
            $query->where('is_active', $isActive);
        }
        
        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        $users = $query->orderBy('created_at', 'desc')->get();
        
        // Aqui você implementaria a lógica de exportação
        // Por enquanto, retornamos os dados em JSON
        return response()->json([
            'message' => 'Relatório gerado com sucesso',
            'format' => $format,
            'total_records' => $users->count(),
            'filters_applied' => array_filter($filters),
            'download_url' => route('admin.users.download-report', ['format' => $format, 'timestamp' => time()])
        ]);
    }

    /**
     * Análise de engajamento dos usuários
     */
    public function getEngagementAnalysis()
    {
        $analysis = [
            'most_active_users' => User::select('id', 'name', 'email')
            ->withCount(['userProgress as completed_modules' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('completed_modules', 'desc')
            ->limit(5)
            ->get(),
            
            'least_active_users' => User::select('id', 'name', 'email')
            ->withCount(['userProgress as completed_modules' => function($query) {
                $query->where('status', 'completed');
            }])
            ->where('is_active', true)
            ->orderBy('completed_modules', 'asc')
            ->limit(5)
            ->get(),
            
            'users_by_login_frequency' => [
                'daily' => User::where('last_login_at', '>=', Carbon::now()->subDay())->count(),
                'weekly' => User::where('last_login_at', '>=', Carbon::now()->subWeek())->count(),
                'monthly' => User::where('last_login_at', '>=', Carbon::now()->subMonth())->count(),
                'never' => User::whereNull('last_login_at')->count(),
            ]
        ];

        return response()->json($analysis);
    }

    /**
     * Sugestões de ações baseadas em dados
     */
    public function getActionSuggestions()
    {
        $suggestions = [];
        
        // Usuários inativos há muito tempo
        $inactiveUsers = User::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<', Carbon::now()->subDays(30));
            })
            ->count();
            
        if ($inactiveUsers > 0) {
            $suggestions[] = [
                'type' => 'warning',
                'title' => 'Usuários Inativos',
                'message' => "{$inactiveUsers} usuários não fazem login há mais de 30 dias",
                'action' => 'Enviar email de reengajamento',
                'priority' => 'high'
            ];
        }
        
        // Usuários sem módulos completados
        $usersWithoutProgress = User::whereDoesntHave('modules', function($query) {
            $query->wherePivot('completed', true);
        })->where('is_active', true)->count();
        
        if ($usersWithoutProgress > 0) {
            $suggestions[] = [
                'type' => 'info',
                'title' => 'Usuários sem Progresso',
                'message' => "{$usersWithoutProgress} usuários ainda não completaram nenhum módulo",
                'action' => 'Criar campanha de onboarding',
                'priority' => 'medium'
            ];
        }
        
        // Muitos administradores
        $adminCount = $this->authService->countAdmins();
        $totalUsers = User::count();
        $adminPercentage = $totalUsers > 0 ? ($adminCount / $totalUsers) * 100 : 0;
        
        if ($adminPercentage > 10) {
            $suggestions[] = [
                'type' => 'security',
                'title' => 'Muitos Administradores',
                'message' => "Você tem {$adminCount} administradores ({$adminPercentage}% do total)",
                'action' => 'Revisar permissões administrativas',
                'priority' => 'high'
            ];
        }

        return response()->json($suggestions);
    }
}