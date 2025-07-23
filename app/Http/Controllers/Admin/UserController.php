<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Exibe a lista de usuários para administração.
     */
    public function index(Request $request): View
    {
        $query = User::query();
        
        // Filtro de busca
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        // Filtro por papel
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }
        
        // Filtro por status
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        $users = $query->orderBy('name')->paginate(15);
        
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
        $user = User::findOrFail($userId);
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
    public function destroy(User $user): RedirectResponse
    {
        // Verificar se há atribuições relacionadas
        if ($user->assignments()->exists() || $user->assignmentsMade()->exists()) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir este usuário pois existem atribuições relacionadas.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }
}
