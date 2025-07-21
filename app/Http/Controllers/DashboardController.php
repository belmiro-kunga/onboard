<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserProgress;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal do usuário.
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
            
        // Buscar notificações do usuário (últimas 5)
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'time' => $notification->created_at->diffForHumans(),
                    'is_read' => $notification->read_at !== null,
                ];
            });
            
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
                'is_available' => $this->isModuleAvailable($module),
            ];
        });
        
        return view('dashboard', compact('modulesWithProgress', 'notifications'));
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