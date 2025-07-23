<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Course;
use App\Models\Module;
use App\Http\Requests\ModuleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseModuleController extends BaseAdminController
{
    protected string $model = Module::class;
    protected string $viewPrefix = 'admin.courses.modules';
    protected string $routePrefix = 'admin.courses.modules';

    /**
     * Exibir listagem de módulos de um curso
     */
    public function index(Course $course, Request $request)
    {
        $query = Module::where('course_id', $course->id)
            ->with(['lessons', 'contents', 'quizzes']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $modules = $query->orderBy('order_index')->paginate(20);

        // Estatísticas do curso
        $stats = [
            'total_modules' => $course->modules()->count(),
            'active_modules' => $course->modules()->where('is_active', true)->count(),
            'total_lessons' => $course->modules()->withCount('lessons')->get()->sum('lessons_count'),
            'total_duration' => $course->modules()->sum('duration_minutes'),
            'avg_completion_rate' => $this->calculateAverageCompletionRate($course),
        ];

        return view("{$this->viewPrefix}.index", compact('course', 'modules', 'stats'));
    }

    /**
     * Exibir formulário de criação de módulo para o curso
     */
    public function create(Course $course)
    {
        // Obter próximo order_index
        $nextOrderIndex = $course->modules()->max('order_index') + 1;

        // Módulos disponíveis para pré-requisitos (do mesmo curso)
        $availableModules = $course->modules()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();

        return view("{$this->viewPrefix}.create", compact('course', 'nextOrderIndex', 'availableModules'));
    }

    /**
     * Armazenar novo módulo no curso
     */
    public function store(Course $course, ModuleRequest $request)
    {
        $data = $request->validated();
        $data['course_id'] = $course->id;

        // Definir order_index se não fornecido
        if (!isset($data['order_index'])) {
            $data['order_index'] = $course->modules()->max('order_index') + 1;
        }

        // Processar thumbnail
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('modules/thumbnails', 'public');
        }

        $module = Module::create($data);

        // Associar pré-requisitos se fornecidos
        if ($request->filled('prerequisites')) {
            $module->prerequisites()->sync($request->prerequisites);
        }

        return redirect()->route('admin.courses.modules.show', [$course, $module])
            ->with('success', 'Módulo criado com sucesso!');
    }

    /**
     * Exibir módulo específico do curso
     */
    public function show(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        $module->load([
            'lessons' => function($query) {
                $query->with(['video', 'materials', 'quiz'])->orderBy('order_index');
            },
            'contents' => function($query) {
                $query->orderBy('order_index');
            },
            'quizzes',
            'prerequisites',
            'userProgress.user'
        ]);

        // Estatísticas do módulo
        $stats = [
            'total_lessons' => $module->lessons()->count(),
            'active_lessons' => $module->lessons()->where('is_active', true)->count(),
            'total_users_enrolled' => $course->enrolledUsers()->count(),
            'users_completed' => $module->userProgress()->where('status', 'completed')->count(),
            'completion_rate' => $module->getCompletionRate(),
            'average_time' => $module->getAverageTime(),
            'total_comments' => $module->lessons()->withCount('comments')->get()->sum('comments_count'),
        ];

        return view("{$this->viewPrefix}.show", compact('course', 'module', 'stats'));
    }

    /**
     * Exibir formulário de edição do módulo
     */
    public function edit(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        $module->load('prerequisites');

        // Módulos disponíveis para pré-requisitos (do mesmo curso, exceto o atual)
        $availableModules = $course->modules()
            ->where('id', '!=', $module->id)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();

        $currentPrerequisites = $module->prerequisites->pluck('id')->toArray();

        return view("{$this->viewPrefix}.edit", compact('course', 'module', 'availableModules', 'currentPrerequisites'));
    }

    /**
     * Atualizar módulo do curso
     */
    public function update(Course $course, Module $module, ModuleRequest $request)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        $data = $request->validated();

        // Processar thumbnail
        if ($request->hasFile('thumbnail')) {
            // Remover thumbnail anterior
            if ($module->thumbnail) {
                Storage::disk('public')->delete($module->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('modules/thumbnails', 'public');
        }

        $module->update($data);

        // Atualizar pré-requisitos
        if ($request->filled('prerequisites')) {
            $module->prerequisites()->sync($request->prerequisites);
        } else {
            $module->prerequisites()->detach();
        }

        return redirect()->route('admin.courses.modules.show', [$course, $module])
            ->with('success', 'Módulo atualizado com sucesso!');
    }

    /**
     * Remover módulo do curso
     */
    public function destroy(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        // Verificar se há progresso de usuários
        if ($module->userProgress()->exists()) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir este módulo pois existem usuários com progresso registrado.');
        }

        // Remover thumbnail
        if ($module->thumbnail) {
            Storage::disk('public')->delete($module->thumbnail);
        }

        // Remover relacionamentos
        $module->prerequisites()->detach();
        $module->dependentModules()->detach();

        // Remover conteúdos relacionados
        foreach ($module->lessons as $lesson) {
            // Remover materiais da aula
            foreach ($lesson->materials as $material) {
                if ($material->file_path) {
                    Storage::disk('public')->delete('materials/' . $material->file_path);
                }
            }
            
            // Remover vídeo da aula
            if ($lesson->video && $lesson->video->file_path) {
                Storage::disk('public')->delete('videos/' . $lesson->video->file_path);
            }
        }

        $module->delete();

        return redirect()->route('admin.courses.modules.index', $course)
            ->with('success', 'Módulo excluído com sucesso!');
    }

    /**
     * Reordenar módulos do curso
     */
    public function reorder(Course $course, Request $request)
    {
        $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order_index' => 'required|integer|min:0',
        ]);

        foreach ($request->modules as $moduleData) {
            Module::where('id', $moduleData['id'])
                ->where('course_id', $course->id)
                ->update(['order_index' => $moduleData['order_index']]);
        }

        return $this->successResponse(['message' => 'Ordem dos módulos atualizada com sucesso!']);
    }

    /**
     * Duplicar módulo
     */
    public function duplicate(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        $newModule = $module->replicate();
        $newModule->title = $module->title . ' (Cópia)';
        $newModule->order_index = $course->modules()->max('order_index') + 1;
        $newModule->save();

        // Duplicar aulas
        foreach ($module->lessons as $lesson) {
            $newLesson = $lesson->replicate();
            $newLesson->module_id = $newModule->id;
            $newLesson->save();

            // Duplicar vídeo da aula
            if ($lesson->video) {
                $newVideo = $lesson->video->replicate();
                $newVideo->lesson_id = $newLesson->id;
                $newVideo->save();
            }

            // Duplicar materiais da aula
            foreach ($lesson->materials as $material) {
                $newMaterial = $material->replicate();
                $newMaterial->lesson_id = $newLesson->id;
                $newMaterial->save();
            }

            // Duplicar quiz da aula
            if ($lesson->quiz) {
                $newQuiz = $lesson->quiz->replicate();
                $newQuiz->lesson_id = $newLesson->id;
                $newQuiz->save();
            }
        }

        // Duplicar conteúdos antigos (se existirem)
        foreach ($module->contents as $content) {
            $newContent = $content->replicate();
            $newContent->module_id = $newModule->id;
            $newContent->save();
        }

        return redirect()->route('admin.courses.modules.edit', [$course, $newModule])
            ->with('success', 'Módulo duplicado com sucesso!');
    }

    /**
     * Alternar status ativo/inativo
     */
    public function toggleActive(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        $module->is_active = !$module->is_active;
        $module->save();

        $status = $module->is_active ? 'ativado' : 'desativado';

        return redirect()->back()
            ->with('success', "Módulo {$status} com sucesso!");
    }

    /**
     * Relatório de progresso do módulo
     */
    public function progressReport(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            abort(404, 'Módulo não encontrado neste curso.');
        }

        $progressData = $module->userProgress()
            ->with('user')
            ->orderBy('progress_percentage', 'desc')
            ->paginate(50);

        $stats = [
            'total_enrolled' => $course->enrolledUsers()->count(),
            'started' => $module->userProgress()->where('progress_percentage', '>', 0)->count(),
            'completed' => $module->userProgress()->where('status', 'completed')->count(),
            'average_progress' => $module->userProgress()->avg('progress_percentage') ?? 0,
            'average_time' => $module->getAverageTime(),
        ];

        return view("{$this->viewPrefix}.progress-report", compact('course', 'module', 'progressData', 'stats'));
    }

    /**
     * Calcular taxa média de conclusão do curso
     */
    private function calculateAverageCompletionRate(Course $course): float
    {
        $modules = $course->modules()->where('is_active', true)->get();
        
        if ($modules->isEmpty()) {
            return 0;
        }

        $totalRate = $modules->sum(function($module) {
            return $module->getCompletionRate();
        });

        return round($totalRate / $modules->count(), 2);
    }
}
