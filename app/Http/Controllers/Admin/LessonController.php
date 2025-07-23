<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Lesson;
use App\Models\Module;
use App\Http\Requests\LessonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends BaseAdminController
{
    protected string $model = Lesson::class;
    protected string $viewPrefix = 'admin.lessons';
    protected string $routePrefix = 'admin.lessons';

    /**
     * Configurar query base
     */
    protected function getBaseQuery()
    {
        return Lesson::with(['module.course', 'video', 'materials', 'quiz']);
    }

    /**
     * Exibir listagem de aulas
     */
    public function index(Request $request)
    {
        $query = $this->getBaseQuery();

        // Filtrar por módulo se especificado
        if ($request->filled('module_id')) {
            $query->where('module_id', $request->module_id);
        }

        // Filtrar por status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Busca por título
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $lessons = $query->orderBy('order_index')->paginate(20);
        $modules = Module::with('course')->orderBy('title')->get();

        return view("{$this->viewPrefix}.index", compact('lessons', 'modules'));
    }

    /**
     * Exibir formulário de criação
     */
    public function create(Request $request)
    {
        $modules = Module::with('course')->where('is_active', true)->orderBy('title')->get();
        $selectedModuleId = $request->get('module_id');

        return view("{$this->viewPrefix}.create", compact('modules', 'selectedModuleId'));
    }

    /**
     * Armazenar nova aula
     */
    public function store(LessonRequest $request)
    {
        $data = $request->validated();
        
        // Definir order_index se não fornecido
        if (!isset($data['order_index'])) {
            $data['order_index'] = Lesson::where('module_id', $data['module_id'])->max('order_index') + 1;
        }

        $lesson = Lesson::create($data);

        // Criar vídeo se fornecido
        if ($request->filled('video_type')) {
            $this->createLessonVideo($lesson, $request);
        }

        // Criar materiais se fornecidos
        if ($request->has('materials')) {
            $this->createLessonMaterials($lesson, $request->materials);
        }

        // Criar quiz se fornecido
        if ($request->filled('quiz_title')) {
            $this->createLessonQuiz($lesson, $request);
        }

        return redirect()->route("{$this->routePrefix}.show", $lesson)
            ->with('success', 'Aula criada com sucesso!');
    }

    /**
     * Exibir aula específica
     */
    public function show(Lesson $lesson)
    {
        $lesson->load([
            'module.course',
            'video',
            'materials',
            'quiz',
            'userProgress.user',
            'comments.user'
        ]);

        $stats = [
            'total_users' => $lesson->module->course->enrolledUsers()->count(),
            'completed_users' => $lesson->userProgress()->where('is_completed', true)->count(),
            'average_progress' => $lesson->userProgress()->avg('progress_percentage') ?? 0,
            'total_comments' => $lesson->comments()->count(),
            'total_questions' => $lesson->comments()->where('is_question', true)->count(),
        ];

        return view("{$this->viewPrefix}.show", compact('lesson', 'stats'));
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(Lesson $lesson)
    {
        $lesson->load(['video', 'materials', 'quiz']);
        $modules = Module::with('course')->where('is_active', true)->orderBy('title')->get();

        return view("{$this->viewPrefix}.edit", compact('lesson', 'modules'));
    }

    /**
     * Atualizar aula
     */
    public function update(LessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();
        $lesson->update($data);

        // Atualizar vídeo
        if ($request->filled('video_type')) {
            $this->updateLessonVideo($lesson, $request);
        }

        // Atualizar materiais
        if ($request->has('materials')) {
            $this->updateLessonMaterials($lesson, $request->materials);
        }

        // Atualizar quiz
        if ($request->filled('quiz_title')) {
            $this->updateLessonQuiz($lesson, $request);
        }

        return redirect()->route("{$this->routePrefix}.show", $lesson)
            ->with('success', 'Aula atualizada com sucesso!');
    }

    /**
     * Reordenar aulas
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'lessons' => 'required|array',
            'lessons.*.id' => 'required|exists:lessons,id',
            'lessons.*.order_index' => 'required|integer|min:0',
        ]);

        foreach ($request->lessons as $lessonData) {
            Lesson::where('id', $lessonData['id'])
                ->update(['order_index' => $lessonData['order_index']]);
        }

        return $this->successResponse(['message' => 'Ordem das aulas atualizada com sucesso!']);
    }

    /**
     * Duplicar aula
     */
    public function duplicate(Lesson $lesson)
    {
        $newLesson = $lesson->replicate();
        $newLesson->title = $lesson->title . ' (Cópia)';
        $newLesson->order_index = Lesson::where('module_id', $lesson->module_id)->max('order_index') + 1;
        $newLesson->save();

        // Duplicar vídeo
        if ($lesson->video) {
            $newVideo = $lesson->video->replicate();
            $newVideo->lesson_id = $newLesson->id;
            $newVideo->save();
        }

        // Duplicar materiais
        foreach ($lesson->materials as $material) {
            $newMaterial = $material->replicate();
            $newMaterial->lesson_id = $newLesson->id;
            $newMaterial->save();
        }

        // Duplicar quiz
        if ($lesson->quiz) {
            $newQuiz = $lesson->quiz->replicate();
            $newQuiz->lesson_id = $newLesson->id;
            $newQuiz->save();
        }

        return redirect()->route("{$this->routePrefix}.edit", $newLesson)
            ->with('success', 'Aula duplicada com sucesso!');
    }

    /**
     * Relatório de engajamento
     */
    public function engagementReport(Lesson $lesson)
    {
        $stats = $lesson->getEngagementStats();
        
        $progressData = $lesson->userProgress()
            ->with('user')
            ->orderBy('progress_percentage', 'desc')
            ->get();

        $commentsData = $lesson->comments()
            ->with('user')
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->get();

        return view("{$this->viewPrefix}.engagement-report", compact(
            'lesson',
            'stats',
            'progressData',
            'commentsData'
        ));
    }

    /**
     * Criar vídeo da aula
     */
    private function createLessonVideo(Lesson $lesson, Request $request)
    {
        $videoData = [
            'type' => $request->video_type,
            'auto_play_next' => $request->boolean('auto_play_next', false),
            'picture_in_picture' => $request->boolean('picture_in_picture', true),
        ];

        if ($request->video_type === 'youtube') {
            $videoData['video_id'] = $this->extractYouTubeId($request->video_url);
            $videoData['video_url'] = $request->video_url;
        } elseif ($request->video_type === 'local' && $request->hasFile('video_file')) {
            $videoData['file_path'] = $request->file('video_file')->store('videos', 'public');
            $videoData['file_size'] = $request->file('video_file')->getSize();
        }

        $lesson->video()->create($videoData);
    }

    /**
     * Atualizar vídeo da aula
     */
    private function updateLessonVideo(Lesson $lesson, Request $request)
    {
        $video = $lesson->video;
        
        if (!$video) {
            return $this->createLessonVideo($lesson, $request);
        }

        $videoData = [
            'type' => $request->video_type,
            'auto_play_next' => $request->boolean('auto_play_next', false),
            'picture_in_picture' => $request->boolean('picture_in_picture', true),
        ];

        if ($request->video_type === 'youtube') {
            $videoData['video_id'] = $this->extractYouTubeId($request->video_url);
            $videoData['video_url'] = $request->video_url;
            $videoData['file_path'] = null;
        } elseif ($request->video_type === 'local' && $request->hasFile('video_file')) {
            // Deletar arquivo anterior se existir
            if ($video->file_path) {
                Storage::disk('public')->delete('videos/' . $video->file_path);
            }
            
            $videoData['file_path'] = $request->file('video_file')->store('videos', 'public');
            $videoData['file_size'] = $request->file('video_file')->getSize();
            $videoData['video_url'] = null;
            $videoData['video_id'] = null;
        }

        $video->update($videoData);
    }

    /**
     * Extrair ID do YouTube da URL
     */
    private function extractYouTubeId(string $url): ?string
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Criar materiais da aula
     */
    private function createLessonMaterials(Lesson $lesson, array $materials)
    {
        foreach ($materials as $index => $materialData) {
            if (empty($materialData['title'])) continue;

            $data = [
                'title' => $materialData['title'],
                'description' => $materialData['description'] ?? null,
                'type' => $materialData['type'],
                'order_index' => $index,
                'is_downloadable' => $materialData['is_downloadable'] ?? true,
            ];

            if ($materialData['type'] === 'link') {
                $data['external_url'] = $materialData['external_url'];
            } elseif (isset($materialData['file']) && $materialData['file']) {
                $data['file_path'] = $materialData['file']->store('materials', 'public');
                $data['file_size'] = $materialData['file']->getSize();
                $data['mime_type'] = $materialData['file']->getMimeType();
            }

            $lesson->materials()->create($data);
        }
    }

    /**
     * Atualizar materiais da aula
     */
    private function updateLessonMaterials(Lesson $lesson, array $materials)
    {
        // Remover materiais existentes que não estão na nova lista
        $existingIds = collect($materials)->pluck('id')->filter();
        $lesson->materials()->whereNotIn('id', $existingIds)->delete();

        foreach ($materials as $index => $materialData) {
            if (empty($materialData['title'])) continue;

            $data = [
                'title' => $materialData['title'],
                'description' => $materialData['description'] ?? null,
                'type' => $materialData['type'],
                'order_index' => $index,
                'is_downloadable' => $materialData['is_downloadable'] ?? true,
            ];

            if ($materialData['type'] === 'link') {
                $data['external_url'] = $materialData['external_url'];
            } elseif (isset($materialData['file']) && $materialData['file']) {
                $data['file_path'] = $materialData['file']->store('materials', 'public');
                $data['file_size'] = $materialData['file']->getSize();
                $data['mime_type'] = $materialData['file']->getMimeType();
            }

            if (isset($materialData['id'])) {
                $lesson->materials()->where('id', $materialData['id'])->update($data);
            } else {
                $lesson->materials()->create($data);
            }
        }
    }

    /**
     * Criar quiz da aula
     */
    private function createLessonQuiz(Lesson $lesson, Request $request)
    {
        $quizData = [
            'title' => $request->quiz_title,
            'description' => $request->quiz_description,
            'type' => $request->quiz_type ?? 'quiz',
            'questions' => $request->quiz_questions ?? [],
            'time_limit_minutes' => $request->quiz_time_limit,
            'max_attempts' => $request->quiz_max_attempts,
            'passing_score' => $request->quiz_passing_score,
            'is_required' => $request->boolean('quiz_is_required', false),
            'show_results_immediately' => $request->boolean('quiz_show_results', true),
        ];

        $lesson->quiz()->create($quizData);
    }

    /**
     * Atualizar quiz da aula
     */
    private function updateLessonQuiz(Lesson $lesson, Request $request)
    {
        $quiz = $lesson->quiz;
        
        if (!$quiz) {
            return $this->createLessonQuiz($lesson, $request);
        }

        $quizData = [
            'title' => $request->quiz_title,
            'description' => $request->quiz_description,
            'type' => $request->quiz_type ?? 'quiz',
            'questions' => $request->quiz_questions ?? [],
            'time_limit_minutes' => $request->quiz_time_limit,
            'max_attempts' => $request->quiz_max_attempts,
            'passing_score' => $request->quiz_passing_score,
            'is_required' => $request->boolean('quiz_is_required', false),
            'show_results_immediately' => $request->boolean('quiz_show_results', true),
        ];

        $quiz->update($quizData);
    }
}