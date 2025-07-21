<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ModuleController extends Controller
{
    /**
     * Exibe a lista de módulos.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Buscar módulos ativos
        $modules = Module::where('is_active', true)
            ->orderBy('order_index')
            ->get();
            
        // Buscar progresso do usuário
        $userProgress = UserProgress::where('user_id', $user->id)
            ->pluck('progress_percentage', 'module_id')
            ->toArray();
            
        // Combinar módulos com progresso do usuário
        $modulesWithProgress = $modules->map(function ($module) use ($userProgress) {
            $progress = $userProgress[$module->id] ?? 0;
            $status = $this->determineModuleStatus($progress);
            
            return [
                'id' => $module->id,
                'title' => $module->title,
                'description' => $module->description,
                'estimated_duration' => $module->estimated_duration,
                'points_reward' => $module->points_reward,
                'completion_percentage' => $progress,
                'status' => $status,
                'user_status' => $status,
                'is_available' => $this->isModuleAvailable($module),
            ];
        });
        
        return view('modules.index', compact('modulesWithProgress'));
    }

    /**
     * Exibe detalhes de um módulo.
     */
    public function show(int $moduleId): View
    {
        $module = Module::findOrFail($moduleId);
        return view('modules.show', compact('module'));
    }

    /**
     * Marca conteúdo como visualizado.
     */
    public function markContentAsViewed(Request $request, int $moduleId, int $contentId): RedirectResponse
    {
        // Lógica para marcar conteúdo como visualizado...
        return redirect()->route('modules.show', $moduleId);
    }

    /**
     * Marca quiz como concluído.
     */
    public function markQuizAsCompleted(Request $request, int $moduleId, int $quizId): RedirectResponse
    {
        // Lógica para marcar quiz como concluído...
        return redirect()->route('modules.show', $moduleId);
    }

    /**
     * Salva anotações do módulo.
     */
    public function saveNotes(Request $request, int $moduleId): RedirectResponse
    {
        // Lógica para salvar anotações...
        return redirect()->route('modules.show', $moduleId);
    }
    
    /**
     * Determina o status do módulo baseado no progresso.
     */
    private function determineModuleStatus(int $progress): string
    {
        if ($progress >= 100) {
            return 'completed';
        } elseif ($progress > 0) {
            return 'in_progress';
        } else {
            return 'not_started';
        }
    }
    
    /**
     * Verifica se o módulo está disponível para o usuário.
     */
    private function isModuleAvailable(Module $module): bool
    {
        // Por enquanto, todos os módulos estão disponíveis
        // Aqui você pode adicionar lógica de pré-requisitos
        return true;
    }
}