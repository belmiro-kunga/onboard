<?php

namespace App\Http\Controllers\Admin;


use App\Http\Responses\ApiResponse;use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ModuleController extends BaseAdminController
{
    /**
     * Exibe a lista de módulos.
     */
    public function index(Request $request)
    {
        $items = $this->baseIndex(Module::class, $request, ['title', 'description']);
        $stats = $this->generateStats(Module::class);
        // Adicionar categorias e níveis de dificuldade
        $categories = Module::query()->distinct()->pluck('category')->filter()->unique()->values();
        $difficultyLevels = ['beginner', 'intermediate', 'advanced'];
        // Ajustar para passar $modules para a view
        $modules = $items;
        return $this->adminView('modules.index', compact('modules', 'stats', 'categories', 'difficultyLevels'));
    }

    /**
     * Exibe o formulário para criar um novo módulo.
     */
    public function create(): View
    {
        // Obter todos os módulos para seleção de pré-requisitos
        $allModules = Module::select('id', 'title')->orderBy('title')->get();
        
        return view('admin.modules.create', compact('allModules'));
    }

    /**
     * Armazena um novo módulo.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'course_id' => 'nullable|exists:courses,id',
            'order_index' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'points_reward' => 'required|integer|min:0',
            'estimated_duration' => 'required|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:0',
            'difficulty_level' => 'required|string|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|image|max:2048',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:modules,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Processar thumbnail se enviada
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('modules/thumbnails', 'public');
            $thumbnailPath = basename($thumbnailPath);
        }
        
        // Criar o módulo
        $module = Module::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'course_id' => $request->course_id,
            'order_index' => $request->order_index,
            'is_active' => $request->has('is_active'),
            'points_reward' => $request->points_reward,
            'estimated_duration' => $request->estimated_duration,
            'duration_minutes' => $request->duration_minutes ?? $request->estimated_duration,
            'content_type' => $request->content_type ?? 'mixed',
            'content_data' => $request->content_data ?? [],
            'thumbnail' => $thumbnailPath,
            'difficulty_level' => $request->difficulty_level,
            'prerequisites' => $request->prerequisites ?? [],
            'requirements' => $request->requirements ?? [],
        ]);
        
        // Associar pré-requisitos
        if ($request->has('prerequisites')) {
            $module->prerequisites()->sync($request->prerequisites);
        }
        
        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um módulo.
     */
    public function show(Module $module): View
    {
        // Carregar relacionamentos
        $module->load(['contents', 'quizzes', 'lessons.video', 'course']);
        
        // Estatísticas
        $completionRate = $module->getCompletionRate();
        $averageTime = $module->getAverageTime();
        $totalUsers = UserProgress::where('module_id', $module->id)->count();
        $completedUsers = UserProgress::where('module_id', $module->id)
            ->where('status', 'completed')
            ->count();
        
        return view('admin.modules.show', compact(
            'module', 
            'completionRate', 
            'averageTime', 
            'totalUsers', 
            'completedUsers'
        ));
    }

    /**
     * Exibe o formulário para editar um módulo.
     */
    public function edit(Module $module): View
    {
        // Carregar relacionamentos necessários
        $module->load(['course', 'lessons']);
        
        // Obter todos os módulos para referência (se necessário no futuro)
        $allModules = Module::where('id', '!=', $module->id)
            ->select('id', 'title')
            ->orderBy('title')
            ->get();
        
        return view('admin.modules.edit', compact('module', 'allModules'));
    }

    /**
     * Atualiza um módulo.
     */
    public function update(Request $request, Module $module)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'order_index' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'points_reward' => 'required|integer|min:0',
            'estimated_duration' => 'required|integer|min:1',
            'difficulty_level' => 'required|string|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|image|max:2048',
            'prerequisites' => 'nullable|string|max:1000', // Mudado para string
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Processar thumbnail se enviada
        if ($request->hasFile('thumbnail')) {
            // Remover thumbnail anterior se existir
            if ($module->thumbnail) {
                Storage::disk('public')->delete('modules/thumbnails/' . $module->thumbnail);
            }
            
            $thumbnailPath = $request->file('thumbnail')->store('modules/thumbnails', 'public');
            $module->thumbnail = basename($thumbnailPath);
        }
        
        // Atualizar o módulo
        $module->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'course_id' => $request->course_id ?? $module->course_id,
            'order_index' => $request->order_index,
            'is_active' => $request->has('is_active'),
            'points_reward' => $request->points_reward,
            'estimated_duration' => $request->estimated_duration,
            'duration_minutes' => $request->duration_minutes ?? $request->estimated_duration,
            'content_type' => $request->content_type ?? $module->content_type,
            'content_data' => $request->content_data ?? $module->content_data,
            'difficulty_level' => $request->difficulty_level,
            'prerequisites' => $request->prerequisites ?? '',
            'requirements' => $request->requirements ?? $module->requirements ?? [],
        ]);
        
        // Não processar pré-requisitos como relacionamento, pois agora é um campo de texto
        // O campo prerequisites agora é armazenado como string no campo prerequisites do módulo
        
        return redirect()->route('admin.modules.show', $module)
            ->with('success', 'Módulo atualizado com sucesso!');
    }

    /**
     * Remove um módulo.
     */
    public function destroy(Module $module)
    {
        // Verificar se há usuários com progresso neste módulo
        $hasProgress = UserProgress::where('module_id', $module->id)->exists();
        
        if ($hasProgress) {
            return redirect()->back()
                ->with('error', 'Não é possível excluir este módulo pois existem usuários com progresso registrado.');
        }
        
        // Remover thumbnail se existir
        if ($module->thumbnail) {
            Storage::disk('public')->delete('modules/thumbnails/' . $module->thumbnail);
        }
        
        // Remover conteúdos do módulo
        $module->contents()->delete();
        
        // Remover quizzes do módulo
        $module->quizzes()->delete();
        
        // Remover relações de pré-requisitos (se existirem)
        if (method_exists($module, 'prerequisites')) {
            $module->prerequisites()->detach();
        }
        if (method_exists($module, 'dependentModules')) {
            $module->dependentModules()->detach();
        }
        
        // Excluir o módulo
        $module->delete();
        
        return redirect()->route('admin.modules.index')
            ->with('success', 'Módulo excluído com sucesso!');
    }
    
    /**
     * Alterna o status de ativação do módulo.
     */
    public function toggleActive(Module $module)
    {
        $module->is_active = !$module->is_active;
        $module->save();
        
        $status = $module->is_active ? 'ativado' : 'desativado';
        
        return redirect()->back()
            ->with('success', "Módulo {$status} com sucesso!");
    }
    
    /**
     * Reordena os módulos.
     */
    public function reorder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order' => 'required|integer|min:0',
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }
        
        foreach ($request->modules as $moduleData) {
            Module::where('id', $moduleData['id'])->update(['order_index' => $moduleData['order']]);
        }
        
        return $this->successResponse();
    }
    
    /**
     * Gerencia os conteúdos de um módulo.
     */
    public function contents(Module $module): View
    {
        $module->load('contents');
        
        return view('admin.modules.contents', compact('module'));
    }
    
    /**
     * Adiciona um conteúdo ao módulo.
     */
    public function addContent(Request $request, Module $module)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content_type' => 'required|string|in:text,video,audio,pdf,link,image',
            'content' => 'required|string',
            'order_index' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:0',
            'file' => 'nullable|file|max:10240',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Processar arquivo se enviado
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('modules/contents', 'public');
            $filePath = basename($filePath);
        }
        
        // Criar o conteúdo
        ModuleContent::create([
            'module_id' => $module->id,
            'title' => $request->title,
            'content_type' => $request->content_type,
            'content' => $request->content,
            'file_path' => $filePath,
            'order_index' => $request->order_index,
            'duration' => $request->duration,
            'is_required' => $request->has('is_required'),
        ]);
        
        return redirect()->route('admin.modules.contents', $module)
            ->with('success', 'Conteúdo adicionado com sucesso!');
    }
    
    /**
     * Remove um conteúdo do módulo.
     */
    public function removeContent(Module $module, ModuleContent $content)
    {
        // Verificar se o conteúdo pertence ao módulo
        if ($content->module_id !== $module->id) {
            return redirect()->back()
                ->with('error', 'Este conteúdo não pertence ao módulo especificado.');
        }
        
        // Remover arquivo se existir
        if ($content->file_path) {
            Storage::disk('public')->delete('modules/contents/' . $content->file_path);
        }
        
        // Excluir o conteúdo
        $content->delete();
        
        return redirect()->route('admin.modules.contents', $module)
            ->with('success', 'Conteúdo removido com sucesso!');
    }
}