<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Course::query();
        
        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        // Ordenação
        $sortField = $request->sort_by ?? 'order_index';
        $sortDirection = $request->sort_direction ?? 'asc';
        $query->orderBy($sortField, $sortDirection);
        
        // Paginação
        $courses = $query->paginate(10)->withQueryString();
        
        // Estatísticas
        $stats = [
            'total' => Course::count(),
            'active' => Course::where('is_active', true)->count(),
            'inactive' => Course::where('is_active', false)->count(),
            'mandatory' => Course::where('type', 'mandatory')->count(),
            'optional' => Course::where('type', 'optional')->count(),
            'certification' => Course::where('type', 'certification')->count(),
        ];
        
        return view('admin.courses.index', compact('courses', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_hours' => 'nullable|integer|min:0',
            'difficulty_level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'type' => ['required', Rule::in(['mandatory', 'optional', 'certification'])],
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
        ]);
        
        // Processar tags
        if (isset($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = array_filter($tags);
        }
        
        // Processar thumbnail
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // Criar curso
        $course = Course::create($validated);
        
        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Curso criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        // Carregar módulos relacionados
        $modules = $course->modules()->orderBy('order_index')->get();
        
        // Estatísticas
        $stats = [
            'modules_count' => $modules->count(),
            'active_modules' => $modules->where('is_active', true)->count(),
            'total_enrollments' => $course->enrollments()->count(),
            'completion_rate' => $course->getCompletionRate(),
            'total_duration' => $modules->sum('duration_minutes') / 60, // em horas
        ];
        
        return view('admin.courses.show', compact('course', 'modules', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:255',
            'thumbnail' => 'nullable|image|max:2048',
            'duration_hours' => 'nullable|integer|min:0',
            'difficulty_level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'type' => ['required', Rule::in(['mandatory', 'optional', 'certification'])],
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
        ]);
        
        // Processar tags
        if (isset($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $validated['tags'] = array_filter($tags);
        }
        
        // Processar thumbnail
        if ($request->hasFile('thumbnail')) {
            // Remover thumbnail anterior se existir
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail'] = $path;
        }
        
        // Atualizar curso
        $course->update($validated);
        
        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            // Verificar se há usuários inscritos
            $enrollmentsCount = $course->enrollments()->count();
            
            if ($enrollmentsCount > 0) {
                // Remover todas as inscrições primeiro
                $course->enrollments()->delete();
            }
            
            // Remover associações com módulos
            $course->modules()->update(['course_id' => null]);
            
            // Remover thumbnail se existir
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            // Excluir curso
            $course->delete();
            
            $message = 'Curso excluído com sucesso!';
            if ($enrollmentsCount > 0) {
                $message .= " {$enrollmentsCount} inscrições foram removidas.";
            }
            
            return redirect()->route('admin.courses.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir o curso: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle course active status
     */
    public function toggleActive(Course $course)
    {
        $course->update([
            'is_active' => !$course->is_active
        ]);
        
        $status = $course->is_active ? 'ativado' : 'desativado';
        
        return back()->with('success', "Curso {$status} com sucesso!");
    }
    
    /**
     * Reorder courses
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'courses' => 'required|array',
            'courses.*.id' => 'required|exists:courses,id',
            'courses.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($validated['courses'] as $courseData) {
            Course::where('id', $courseData['id'])->update([
                'order_index' => $courseData['order']
            ]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show course modules
     */
    public function modules(Course $course)
    {
        $modules = $course->modules()->orderBy('order_index')->get();
        $availableModules = Module::whereNull('course_id')->orWhere('course_id', $course->id)->get();
        
        return view('admin.courses.modules', compact('course', 'modules', 'availableModules'));
    }
    
    /**
     * Add module to course
     */
    public function addModule(Request $request, Course $course)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'order_index' => 'nullable|integer|min:0',
        ]);
        
        $module = Module::findOrFail($validated['module_id']);
        
        // Definir ordem se não especificada
        if (!isset($validated['order_index'])) {
            $validated['order_index'] = $course->modules()->max('order_index') + 1;
        }
        
        // Atualizar módulo
        $module->update([
            'course_id' => $course->id,
            'order_index' => $validated['order_index']
        ]);
        
        return back()->with('success', 'Módulo adicionado ao curso com sucesso!');
    }
    
    /**
     * Remove module from course
     */
    public function removeModule(Course $course, Module $module)
    {
        // Verificar se o módulo pertence ao curso
        if ($module->course_id !== $course->id) {
            return back()->with('error', 'Este módulo não pertence ao curso especificado.');
        }
        
        // Remover módulo do curso
        $module->update([
            'course_id' => null,
            'order_index' => null
        ]);
        
        return back()->with('success', 'Módulo removido do curso com sucesso!');
    }
    
    /**
     * Reorder modules in course
     */
    /**
     * Reorder modules in course
     */
    public function reorderModules(Request $request, Course $course)
    {
        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*.id' => 'required|exists:modules,id',
            'modules.*.order' => 'required|integer|min:1',
        ]);
        
        foreach ($validated['modules'] as $moduleData) {
            Module::where('id', $moduleData['id'])
                ->where('course_id', $course->id)
                ->update(['order_index' => $moduleData['order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show course enrollments
     */
    public function enrollments(Course $course)
    {
        $enrollments = $course->enrollments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.courses.enrollments', compact('course', 'enrollments'));
    }
    
    /**
     * Enroll users in course
     */
    public function enrollUsers(Request $request, Course $course)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);
        
        $enrolledCount = 0;
        $alreadyEnrolledCount = 0;
        
        foreach ($validated['user_ids'] as $userId) {
            // Verificar se já está inscrito
            $exists = $course->enrollments()->where('user_id', $userId)->exists();
            
            if (!$exists) {
                $course->enrollments()->create([
                    'user_id' => $userId,
                    'enrolled_at' => now(),
                    'status' => 'enrolled'
                ]);
                
                $enrolledCount++;
            } else {
                $alreadyEnrolledCount++;
            }
        }
        
        $message = "{$enrolledCount} usuários inscritos com sucesso!";
        if ($alreadyEnrolledCount > 0) {
            $message .= " {$alreadyEnrolledCount} usuários já estavam inscritos.";
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Get available users for enrollment
     */
    public function getAvailableUsers(Course $course)
    {
        // Obter IDs de usuários já inscritos
        $enrolledUserIds = $course->enrollments()->pluck('user_id')->toArray();
        
        // Obter usuários não inscritos
        $users = User::whereNotIn('id', $enrolledUserIds)
            ->where('is_active', true)
            ->select('id', 'name', 'email', 'department', 'position')
            ->orderBy('name')
            ->get();
        
        return response()->json($users);
    }
    
    /**
     * Show course reports
     */
    public function reports(Course $course)
    {
        // Estatísticas gerais
        $stats = [
            'total_enrollments' => $course->enrollments()->count(),
            'completed' => $course->enrollments()->where('status', 'completed')->count(),
            'in_progress' => $course->enrollments()->where('status', 'in_progress')->count(),
            'enrolled' => $course->enrollments()->where('status', 'enrolled')->count(),
            'dropped' => $course->enrollments()->where('status', 'dropped')->count(),
            'completion_rate' => $course->getCompletionRate(),
            'avg_completion_days' => $course->enrollments()
                ->where('status', 'completed')
                ->whereNotNull('started_at')
                ->whereNotNull('completed_at')
                ->selectRaw('AVG(DATEDIFF(completed_at, started_at)) as avg_days')
                ->first()
                ->avg_days ?? 0,
        ];
        
        // Progresso por departamento
        $departmentStats = $course->enrollments()
            ->join('users', 'users.id', '=', 'course_enrollments.user_id')
            ->selectRaw('users.department, COUNT(*) as total, SUM(CASE WHEN course_enrollments.status = "completed" THEN 1 ELSE 0 END) as completed')
            ->whereNotNull('users.department')
            ->groupBy('users.department')
            ->get()
            ->map(function($item) {
                $item->completion_rate = $item->total > 0 ? round(($item->completed / $item->total) * 100, 1) : 0;
                return $item;
            });
        
        return view('admin.courses.reports', compact('course', 'stats', 'departmentStats'));
    }
}