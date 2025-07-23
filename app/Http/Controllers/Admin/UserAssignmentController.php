<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\User;
use App\Models\UserAssignment;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserAssignmentController extends BaseAdminController
{
    /**
     * Exibe a lista de atribuições
     */
    public function index(Request $request)
    {
        $query = UserAssignment::with(['user', 'assignedBy', 'assignable']);
        
        // Filtros
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('type') && $request->type) {
            $query->where('assignable_type', $request->type);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('overdue') && $request->overdue === '1') {
            $query->overdue();
        }
        
        // Ordenação
        $assignments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Dados para filtros
        $users = User::where('is_active', true)->orderBy('name')->get();
        $types = [
            'App\Models\Course' => 'Cursos',
            'App\Models\Quiz' => 'Quizzes',
            'App\Models\Module' => 'Módulos',
        ];
        
        // Estatísticas
        $stats = [
            'total' => UserAssignment::count(),
            'assigned' => UserAssignment::where('status', 'assigned')->count(),
            'in_progress' => UserAssignment::where('status', 'in_progress')->count(),
            'completed' => UserAssignment::where('status', 'completed')->count(),
            'overdue' => UserAssignment::overdue()->count(),
        ];
        
        return view('admin.assignments.index', compact('assignments', 'users', 'types', 'stats'));
    }

    /**
     * Exibe o formulário para criar nova atribuição
     */
    public function create(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $quizzes = Quiz::where('is_active', true)->orderBy('title')->get();
        $modules = Module::where('is_active', true)->orderBy('title')->get();
        
        // Se um usuário foi especificado na URL
        $selectedUser = $request->user_id ? User::find($request->user_id) : null;
        
        return view('admin.assignments.create', compact('users', 'courses', 'quizzes', 'modules', 'selectedUser'));
    }

    /**
     * Armazena uma nova atribuição
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'assignable_type' => 'required|in:course,quiz,module',
            'assignable_id' => 'required|integer',
            'due_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'is_mandatory' => 'boolean',
        ]);

        // Mapear tipos para classes
        $typeMap = [
            'course' => Course::class,
            'quiz' => Quiz::class,
            'module' => Module::class,
        ];

        $assignableType = $typeMap[$validated['assignable_type']];
        
        // Verificar se o item existe
        $assignable = $assignableType::find($validated['assignable_id']);
        if (!$assignable) {
            return back()->withErrors(['assignable_id' => 'Item selecionado não encontrado.']);
        }

        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($validated, $assignableType, &$createdCount, &$skippedCount) {
            foreach ($validated['user_ids'] as $userId) {
                // Verificar se já existe atribuição
                $exists = UserAssignment::where([
                    'user_id' => $userId,
                    'assignable_type' => $assignableType,
                    'assignable_id' => $validated['assignable_id'],
                ])->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                UserAssignment::create([
                    'user_id' => $userId,
                    'assignable_type' => $assignableType,
                    'assignable_id' => $validated['assignable_id'],
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                    'due_date' => $validated['due_date'],
                    'notes' => $validated['notes'],
                    'is_mandatory' => $validated['is_mandatory'] ?? false,
                    'status' => 'assigned',
                ]);

                $createdCount++;
            }
        });

        $message = "{$createdCount} atribuições criadas com sucesso!";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} atribuições já existiam e foram ignoradas.";
        }

        return redirect()->route('admin.assignments.index')->with('success', $message);
    }

    /**
     * Exibe detalhes de uma atribuição
     */
    public function show(UserAssignment $assignment)
    {
        $assignment->load(['user', 'assignedBy', 'assignable']);
        return view('admin.assignments.show', compact('assignment'));
    }

    /**
     * Exibe o formulário de edição
     */
    public function edit(UserAssignment $assignment)
    {
        $assignment->load(['user', 'assignable']);
        return view('admin.assignments.edit', compact('assignment'));
    }

    /**
     * Atualiza uma atribuição
     */
    public function update(Request $request, UserAssignment $assignment)
    {
        $validated = $request->validate([
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'is_mandatory' => 'boolean',
            'status' => 'required|in:assigned,in_progress,completed,overdue',
        ]);

        $assignment->update($validated);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Atribuição atualizada com sucesso!');
    }

    /**
     * Remove uma atribuição
     */
    public function destroy(UserAssignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Atribuição removida com sucesso!');
    }

    /**
     * Página para atribuir itens a um usuário específico
     */
    public function assignToUser(User $user)
    {
        $courses = Course::where('is_active', true)->orderBy('title')->get();
        $quizzes = Quiz::where('is_active', true)->orderBy('title')->get();
        $modules = Module::where('is_active', true)->orderBy('title')->get();
        
        // Atribuições existentes do usuário
        $existingAssignments = UserAssignment::where('user_id', $user->id)
            ->with('assignable')
            ->get()
            ->groupBy('assignable_type');

        return view('admin.assignments.assign-to-user', compact('user', 'courses', 'quizzes', 'modules', 'existingAssignments'));
    }

    /**
     * Atribuir múltiplos itens a um usuário
     */
    public function storeUserAssignments(Request $request, User $user)
    {
        $validated = $request->validate([
            'assignments' => 'required|array',
            'assignments.*.type' => 'required|in:course,quiz,module',
            'assignments.*.id' => 'required|integer',
            'assignments.*.due_date' => 'nullable|date|after:today',
            'assignments.*.is_mandatory' => 'boolean',
            'assignments.*.notes' => 'nullable|string|max:500',
        ]);

        $typeMap = [
            'course' => Course::class,
            'quiz' => Quiz::class,
            'module' => Module::class,
        ];

        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($validated, $user, $typeMap, &$createdCount, &$skippedCount) {
            foreach ($validated['assignments'] as $assignment) {
                $assignableType = $typeMap[$assignment['type']];
                
                // Verificar se já existe
                $exists = UserAssignment::where([
                    'user_id' => $user->id,
                    'assignable_type' => $assignableType,
                    'assignable_id' => $assignment['id'],
                ])->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                UserAssignment::create([
                    'user_id' => $user->id,
                    'assignable_type' => $assignableType,
                    'assignable_id' => $assignment['id'],
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                    'due_date' => $assignment['due_date'] ?? null,
                    'notes' => $assignment['notes'] ?? null,
                    'is_mandatory' => $assignment['is_mandatory'] ?? false,
                    'status' => 'assigned',
                ]);

                $createdCount++;
            }
        });

        $message = "{$createdCount} itens atribuídos com sucesso!";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} itens já estavam atribuídos.";
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', $message);
    }

    /**
     * Atualizar status de uma atribuição via AJAX
     */
    public function updateStatus(Request $request, UserAssignment $assignment)
    {
        $validated = $request->validate([
            'status' => 'required|in:assigned,in_progress,completed,overdue',
        ]);

        $assignment->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!',
            'status' => $assignment->status,
            'status_label' => $assignment->status_label,
            'status_color' => $assignment->status_color,
        ]);
    }

    /**
     * Obter dados para dashboard de atribuições
     */
    public function dashboard()
    {
        $stats = [
            'total_assignments' => UserAssignment::count(),
            'pending' => UserAssignment::whereIn('status', ['assigned', 'in_progress'])->count(),
            'completed' => UserAssignment::where('status', 'completed')->count(),
            'overdue' => UserAssignment::overdue()->count(),
            'mandatory_pending' => UserAssignment::mandatory()
                ->whereIn('status', ['assigned', 'in_progress'])
                ->count(),
        ];

        // Atribuições por tipo
        $assignmentsByType = UserAssignment::select('assignable_type', DB::raw('count(*) as total'))
            ->groupBy('assignable_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $typeName = match($item->assignable_type) {
                    'App\Models\Course' => 'Cursos',
                    'App\Models\Quiz' => 'Quizzes',
                    'App\Models\Module' => 'Módulos',
                    default => 'Outros'
                };
                return [$typeName => $item->total];
            });

        // Usuários com mais atribuições pendentes
        $usersWithPendingAssignments = User::withCount(['assignments' => function ($query) {
                $query->whereIn('status', ['assigned', 'in_progress']);
            }])
            ->having('assignments_count', '>', 0)
            ->orderBy('assignments_count', 'desc')
            ->limit(10)
            ->get();

        // Atribuições em atraso
        $overdueAssignments = UserAssignment::overdue()
            ->with(['user', 'assignable'])
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        return view('admin.assignments.dashboard', compact(
            'stats', 
            'assignmentsByType', 
            'usersWithPendingAssignments', 
            'overdueAssignments'
        ));
    }
}