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
    public function show(Module $module): View
    {
        $user = auth()->user();
        
        // Verificar se o módulo está ativo
        if (!$module->is_active) {
            return redirect()->route('courses.index')
                ->with('error', 'Este módulo não está disponível no momento.');
        }
        
        // Verificar se o módulo pertence a um curso
        $course = $module->course;
        
        // Se o módulo pertence a um curso, verificar se o usuário está inscrito
        if ($course) {
            $enrollment = $user->courseEnrollments()
                ->where('course_id', $course->id)
                ->first();
            
            if (!$enrollment) {
                return redirect()->route('courses.show', $course)
                    ->with('error', 'Você precisa se inscrever no curso para acessar este módulo.');
            }
            
            // Se o usuário não iniciou o curso, marcar como iniciado
            if ($enrollment->status === 'enrolled') {
                $enrollment->markAsStarted();
            }
        }
        
        // Obter progresso do usuário neste módulo
        $progress = $user->progress()
            ->where('module_id', $module->id)
            ->first();
        
        // Se não houver registro de progresso, criar um
        if (!$progress) {
            $progress = UserProgress::create([
                'user_id' => $user->id,
                'module_id' => $module->id,
                'status' => 'in_progress',
                'progress_percentage' => 0,
                'started_at' => now()
            ]);
        }
        
        // Carregar conteúdos do módulo
        $contents = $module->contents()
            ->orderBy('order_index')
            ->get();
        
        // Carregar quizzes do módulo
        $quizzes = $module->quizzes()
            ->orderBy('order_index')
            ->get();
        
        return view('modules.show', compact('module', 'course', 'progress', 'contents', 'quizzes'));
    }

    /**
     * Exibe um vídeo específico de um módulo.
     */
    public function showVideo(int $moduleId, int $videoIndex = 0): View
    {
        $moduleData = Module::findOrFail($moduleId);
        $user = auth()->user();
        
        // Simular dados de vídeos para demonstração
        $videos = [
            [
                'title' => 'Introdução ao ' . $moduleData->title,
                'youtube_id' => 'dQw4w9WgXcQ', // Video de exemplo
                'duration' => '10:30',
                'views' => '1.2K',
                'published' => 'há 2 dias',
                'likes' => '45',
                'progress' => 0,
                'completed' => false
            ],
            [
                'title' => 'Conceitos Fundamentais',
                'youtube_id' => 'dQw4w9WgXcQ',
                'duration' => '15:45',
                'views' => '980',
                'published' => 'há 3 dias',
                'likes' => '32',
                'progress' => 0,
                'completed' => false
            ],
            [
                'title' => 'Aplicação Prática',
                'youtube_id' => 'dQw4w9WgXcQ',
                'duration' => '12:20',
                'views' => '756',
                'published' => 'há 4 dias',
                'likes' => '28',
                'progress' => 0,
                'completed' => false
            ],
            [
                'title' => 'Exercícios Práticos',
                'youtube_id' => 'dQw4w9WgXcQ',
                'duration' => '18:15',
                'views' => '654',
                'published' => 'há 5 dias',
                'likes' => '22',
                'progress' => 0,
                'completed' => false
            ],
            [
                'title' => 'Conclusão e Próximos Passos',
                'youtube_id' => 'dQw4w9WgXcQ',
                'duration' => '8:45',
                'views' => '543',
                'published' => 'há 6 dias',
                'likes' => '18',
                'progress' => 0,
                'completed' => false
            ]
        ];
        
        // Validar índice do vídeo
        $currentVideoIndex = max(0, min($videoIndex, count($videos) - 1));
        $currentVideo = $videos[$currentVideoIndex];
        $completedVideos = count(array_filter($videos, fn($v) => $v['completed']));
        
        // Preparar dados do módulo no formato esperado pela view
        $module = [
            'id' => $moduleData->id,
            'title' => $moduleData->title,
            'description' => $moduleData->description,
            'videos' => $videos,
            'total_duration' => $moduleData->formatted_duration,
            'difficulty' => $moduleData->formatted_difficulty,
            'points' => $moduleData->points_reward,
            'progress' => $moduleData->getCompletionPercentageFor($user)
        ];
        
        return view('modules.show', compact('module', 'currentVideo', 'currentVideoIndex', 'completedVideos'));
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