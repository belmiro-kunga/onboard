<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends BaseController
{
    /**
     * Exibir aula específica
     */
    public function show(Lesson $lesson, Request $request)
    {
        $user = Auth::user();
        
        // Verificar se a aula está disponível para o usuário
        if (!$lesson->isAvailableFor($user)) {
            return redirect()->route('modules.show', $lesson->module)
                ->with('error', 'Esta aula não está disponível no momento.');
        }

        // Carregar relacionamentos necessários
        $lesson->load([
            'video',
            'materials' => function($query) {
                $query->orderBy('order_index');
            },
            'quiz',
            'comments' => function($query) {
                $query->with(['user', 'replies.user'])
                      ->whereNull('parent_id')
                      ->orderBy('created_at', 'desc');
            },
            'userNotes' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orderBy('video_timestamp');
            }
        ]);

        // Obter progresso do usuário
        $progress = $lesson->getProgressFor($user);
        
        // Obter aulas anterior e próxima
        $previousLesson = $lesson->getPreviousLesson();
        $nextLesson = $lesson->getNextLesson();

        // Obter estatísticas de engajamento
        $engagementStats = $lesson->getEngagementStats();

        // Verificar timestamp do vídeo na URL
        $videoTimestamp = $request->get('t', 0);

        return view('lessons.show', compact(
            'lesson',
            'progress',
            'previousLesson',
            'nextLesson',
            'engagementStats',
            'videoTimestamp'
        ));
    }

    /**
     * Marcar aula como completada
     */
    public function markCompleted(Lesson $lesson)
    {
        $user = Auth::user();
        
        if (!$lesson->isAvailableFor($user)) {
            return $this->errorResponse('Aula não disponível', 403);
        }

        $progress = $lesson->markAsCompletedFor($user);

        return $this->successResponse([
            'message' => 'Aula marcada como completada!',
            'progress' => $progress,
            'next_lesson' => $lesson->getNextLesson(),
        ]);
    }

    /**
     * Atualizar progresso da aula
     */
    public function updateProgress(Lesson $lesson, Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'watch_time_seconds' => 'nullable|integer|min:0',
            'current_time_seconds' => 'nullable|integer|min:0',
        ]);

        $progress = $lesson->userProgress()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'progress_percentage' => $request->progress_percentage,
                'watch_time_seconds' => $request->watch_time_seconds,
                'current_time_seconds' => $request->current_time_seconds,
                'last_watched_at' => now(),
            ]
        );

        // Auto-completar se chegou a 90%
        if ($request->progress_percentage >= 90 && !$progress->is_completed) {
            $progress->markAsCompleted();
        }

        return $this->successResponse([
            'progress' => $progress,
            'is_completed' => $progress->is_completed,
        ]);
    }

    /**
     * Adicionar comentário à aula
     */
    public function addComment(Lesson $lesson, Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:lesson_comments,id',
            'video_timestamp' => 'nullable|integer|min:0',
            'is_question' => 'boolean',
        ]);

        $comment = $lesson->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id,
            'video_timestamp' => $request->video_timestamp,
            'is_question' => $request->boolean('is_question', false),
        ]);

        $comment->load('user');

        return $this->successResponse([
            'message' => 'Comentário adicionado com sucesso!',
            'comment' => $comment,
        ]);
    }

    /**
     * Adicionar nota pessoal
     */
    public function addNote(Lesson $lesson, Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'content' => 'required|string|max:500',
            'video_timestamp' => 'nullable|integer|min:0',
            'color' => 'nullable|string|in:yellow,blue,green,red,purple',
        ]);

        $note = $lesson->userNotes()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'video_timestamp' => $request->video_timestamp,
            'color' => $request->color ?? 'yellow',
        ]);

        return $this->successResponse([
            'message' => 'Nota adicionada com sucesso!',
            'note' => $note,
        ]);
    }

    /**
     * Download de material da aula
     */
    public function downloadMaterial(Lesson $lesson, $materialId)
    {
        $user = Auth::user();
        
        if (!$lesson->isAvailableFor($user)) {
            abort(403, 'Aula não disponível');
        }

        $material = $lesson->materials()->findOrFail($materialId);
        
        if (!$material->is_downloadable) {
            abort(403, 'Material não disponível para download');
        }

        // Registrar download
        $material->recordDownload($user);

        // Retornar arquivo
        return response()->download($material->getFullFilePath());
    }

    /**
     * Obter estatísticas da aula (para admins)
     */
    public function stats(Lesson $lesson)
    {
        $this->authorize('viewAny', Lesson::class);

        $stats = [
            'engagement' => $lesson->getEngagementStats(),
            'comments' => [
                'total' => $lesson->comments()->count(),
                'questions' => $lesson->comments()->questions()->count(),
                'resolved' => $lesson->comments()->resolved()->count(),
            ],
            'materials' => [
                'total' => $lesson->materials()->count(),
                'downloads' => $lesson->materials()->sum('metadata->download_count'),
            ],
            'quiz' => $lesson->quiz ? $lesson->quiz->getStats() : null,
        ];

        return $this->successResponse($stats);
    }

    /**
     * Obter próxima aula recomendada
     */
    public function getNextRecommended(Lesson $lesson)
    {
        $user = Auth::user();
        $nextLesson = $lesson->getNextLesson();

        if (!$nextLesson) {
            // Se não há próxima aula no módulo, verificar próximo módulo
            $nextModule = Module::where('course_id', $lesson->module->course_id)
                ->where('order_index', '>', $lesson->module->order_index)
                ->where('is_active', true)
                ->orderBy('order_index')
                ->first();

            if ($nextModule) {
                $nextLesson = $nextModule->lessons()
                    ->where('is_active', true)
                    ->orderBy('order_index')
                    ->first();
            }
        }

        return $this->successResponse([
            'next_lesson' => $nextLesson,
            'can_access' => $nextLesson ? $nextLesson->isAvailableFor($user) : false,
        ]);
    }
}