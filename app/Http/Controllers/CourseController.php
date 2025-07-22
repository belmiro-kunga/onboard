<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends BaseController
{
    /**
     * Display a listing of available courses.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obter cursos ativos
        $courses = Course::where('is_active', true)
            ->orderBy('order_index')
            ->orderBy('title')
            ->get();
        
        // Obter IDs dos cursos em que o usuário está inscrito
        $enrolledCourseIds = $user->courseEnrollments()->pluck('course_id')->toArray();
        
        // Separar cursos em categorias
        $enrolledCourses = $courses->filter(function($course) use ($enrolledCourseIds) {
            return in_array($course->id, $enrolledCourseIds);
        });
        
        $availableCourses = $courses->filter(function($course) use ($enrolledCourseIds) {
            return !in_array($course->id, $enrolledCourseIds);
        });
        
        // Estatísticas
        $stats = [
            'total_courses' => $courses->count(),
            'enrolled_courses' => $enrolledCourses->count(),
            'completed_courses' => $user->completedCourses()->count(),
            'in_progress_courses' => $user->courseEnrollments()->where('status', 'in_progress')->count(),
        ];
        
        return view('courses.index', compact('enrolledCourses', 'availableCourses', 'stats'));
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Verificar se o curso está ativo
        if (!$course->is_active) {
            return redirect()->route('courses.index')
                ->with('error', 'Este curso não está disponível no momento.');
        }
        
        // Carregar módulos ativos do curso
        $modules = $course->modules()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();
        
        // Verificar se o usuário está inscrito
        $enrollment = $user->courseEnrollments()
            ->where('course_id', $course->id)
            ->first();
        
        // Verificar se o usuário pode se inscrever
        $canEnroll = $course->canUserEnroll($user);
        
        // Estatísticas
        $stats = [
            'modules_count' => $modules->count(),
            'total_duration' => $modules->sum('duration_minutes') / 60, // em horas
            'enrolled_users' => $course->enrollments()->count(),
            'completion_rate' => $course->getCompletionRate(),
        ];
        
        return view('courses.show', compact('course', 'modules', 'enrollment', 'canEnroll', 'stats'));
    }

    /**
     * Enroll the user in a course.
     */
    public function enroll(Course $course)
    {
        $user = Auth::user();
        
        // Verificar se o curso está ativo
        if (!$course->is_active) {
            return redirect()->route('courses.index')
                ->with('error', 'Este curso não está disponível no momento.');
        }
        
        // Verificar se o usuário já está inscrito
        $existingEnrollment = $user->courseEnrollments()
            ->where('course_id', $course->id)
            ->first();
        
        if ($existingEnrollment) {
            return redirect()->route('courses.show', $course)
                ->with('info', 'Você já está inscrito neste curso.');
        }
        
        // Verificar se o usuário pode se inscrever
        if (!$course->canUserEnroll($user)) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Você não atende aos requisitos para se inscrever neste curso.');
        }
        
        // Criar inscrição
        $enrollment = CourseEnrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'status' => 'enrolled',
            'progress_percentage' => 0
        ]);
        
        // Adicionar pontos de gamificação (se aplicável)
        if (method_exists($user, 'addPoints')) {
            $user->addPoints(10, "Inscrição no curso: {$course->title}");
        }
        
        return redirect()->route('courses.show', $course)
            ->with('success', 'Você foi inscrito no curso com sucesso!');
    }

    /**
     * Start a course.
     */
    public function start(Course $course)
    {
        $user = Auth::user();
        
        // Verificar se o usuário está inscrito
        $enrollment = $user->courseEnrollments()
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Você precisa se inscrever neste curso primeiro.');
        }
        
        // Atualizar status da inscrição
        $enrollment->markAsStarted();
        
        // Redirecionar para o primeiro módulo
        $firstModule = $course->modules()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->first();
        
        if ($firstModule) {
            return redirect()->route('modules.show', $firstModule)
                ->with('success', 'Curso iniciado! Bom aprendizado!');
        }
        
        return redirect()->route('courses.show', $course)
            ->with('info', 'Curso iniciado, mas não há módulos disponíveis no momento.');
    }

    /**
     * Display the course progress.
     */
    public function progress(Course $course)
    {
        $user = Auth::user();
        
        // Verificar se o usuário está inscrito
        $enrollment = $user->courseEnrollments()
            ->where('course_id', $course->id)
            ->first();
        
        if (!$enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Você precisa se inscrever neste curso primeiro.');
        }
        
        // Carregar módulos do curso
        $modules = $course->modules()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();
        
        // Obter progresso por módulo
        $moduleProgress = [];
        foreach ($modules as $module) {
            $progress = $user->progress()
                ->where('module_id', $module->id)
                ->first();
            
            $moduleProgress[$module->id] = [
                'module' => $module,
                'status' => $progress ? $progress->status : 'not_started',
                'percentage' => $progress ? $progress->progress_percentage : 0,
                'completed_at' => $progress && $progress->status === 'completed' ? $progress->completed_at : null
            ];
        }
        
        return view('courses.progress', compact('course', 'enrollment', 'modules', 'moduleProgress'));
    }

    /**
     * Display the course certificate.
     */
    public function certificate(Course $course)
    {
        $user = Auth::user();
        
        // Verificar se o usuário completou o curso
        $enrollment = $user->courseEnrollments()
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();
        
        if (!$enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'Você precisa completar o curso para obter o certificado.');
        }
        
        // Verificar se o certificado já foi gerado
        $certificate = $user->certificates()
            ->where('course_id', $course->id)
            ->first();
        
        if (!$certificate) {
            // Gerar certificado
            $certificateService = app(\App\Services\CertificateService::class);
            $certificate = $certificateService->generateForCourse($user, $course);
        }
        
        return view('courses.certificate', compact('course', 'certificate', 'enrollment'));
    }
}