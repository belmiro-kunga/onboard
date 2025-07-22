<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Exibe o perfil do usuário autenticado.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Exibe o formulário de edição do perfil.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Atualiza os dados do perfil do usuário.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'bio' => 'nullable|string|max:500',
        ]);
        $user->update($validated);
        return redirect()->route('profile.index')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Exibe o formulário de alteração de senha.
     */
    public function changePassword(): View
    {
        return view('profile.change-password');
    }

    /**
     * Atualiza a senha do usuário autenticado.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect()->route('profile.index')->with('success', 'Senha alterada com sucesso!');
    }
}
