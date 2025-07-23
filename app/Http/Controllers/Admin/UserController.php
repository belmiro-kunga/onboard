<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends BaseAdminController
{
    /**
     * Exibe a lista de usuários para administração.
     */
        public function index(Request $request)
    {
        $items = $this->baseIndex(User::class, $request, ['name', 'email']);
        $stats = $this->generateStats(User::class);
        
        return $this->adminView('users.index', compact('items', 'stats'));
    }

    /**
     * Get filtered users query
     */
    protected function getFilteredQuery($request)
    {
        $query = User::query();
        
        // Add search filter if search parameter exists
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        // Add role filter if role parameter exists
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }
        
        // Add status filter if status parameter exists
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $users = $query->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Exibe o formulário de criação de usuário.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Armazena um novo usuário.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,employee',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['is_active'] = $request->has('is_active');

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Exibe detalhes de um usuário específico.
     */
    public function show(int $userId): View
    {
        $user = User::with(['gamification', 'progress', 'achievements'])->findOrFail($userId);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Exibe o formulário de edição de usuário.
     */
    public function edit(int $userId): View
    {
        $user = User::findOrFail($userId);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualiza um usuário existente.
     */
    public function update(Request $request, int $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,employee',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Only update password if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $validatedData['is_active'] = $request->has('is_active');

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove um usuário.
     */
    public function destroy(int $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuário removido com sucesso!');
    }

    /**
     * Alterna o status ativo/inativo do usuário.
     */
    public function toggleActive(int $userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            // Não permitir desativar o próprio usuário
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Você não pode desativar sua própria conta.'
                ], 400);
            }
            
            $user->is_active = !$user->is_active;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => $user->is_active ? 'Usuário ativado com sucesso!' : 'Usuário desativado com sucesso!',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao alterar status do usuário.'
            ], 500);
        }
    }

    /**
     * Executa ações em massa nos usuários selecionados.
     */
    public function bulkActionUsers(Request $request)
    {
        // Validar dados de entrada
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|json'
        ]);

        try {
            $userIds = json_decode($validated['users'], true);
            $currentUserId = auth()->id();
            
            // Remover o usuário atual da lista para evitar auto-modificação
            $userIds = array_filter($userIds, fn($id) => $id != $currentUserId);
            
            if (empty($userIds)) {
                return $this->backWithError('Nenhum usuário válido selecionado.');
            }
            
            // Preparar request para o método pai
            $bulkRequest = new Request([
                'action' => $validated['action'],
                'ids' => $userIds
            ]);
            
            return parent::bulkAction($bulkRequest, User::class, ['activate', 'deactivate', 'delete']);
            
        } catch (\Exception $e) {
            return $this->backWithError('Erro na ação em lote: ' . $e->getMessage());
        }
    }
}
