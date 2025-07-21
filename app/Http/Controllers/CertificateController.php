<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\UserProgress;
use Carbon\Carbon;

class CertificateController extends Controller
{
    /**
     * Exibe a lista de certificados do usuário.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Buscar certificados do usuário
        $certificates = Certificate::where('user_id', $user->id)
            ->with(['quiz', 'attempt'])
            ->orderBy('issued_at', 'desc')
            ->paginate(12);
        
        // Calcular estatísticas
        $stats = $this->getCertificateStats($user);
        
        // Buscar módulos completados
        $completedModules = $this->getCompletedModules($user);
        
        // Buscar quizzes aprovados
        $passedQuizzes = $this->getPassedQuizzes($user);
        
        return view('certificates.index', compact('certificates', 'stats', 'completedModules', 'passedQuizzes'));
    }

    /**
     * Exibe detalhes de um certificado específico.
     */
    public function show(int $certificateId): View
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $certificate = Certificate::where('user_id', $user->id)
            ->with(['user', 'quiz', 'attempt'])
            ->findOrFail($certificateId);
        
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Faz o download de um certificado.
     */
    public function download(int $certificateId): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401);
        }
        
        $certificate = Certificate::where('user_id', $user->id)
            ->findOrFail($certificateId);
        
        // Se não tem arquivo, gerar um
        if (!$certificate->file_path || !file_exists($certificate->file_path)) {
            $certificate = $this->generateCertificateFile($certificate);
        }
        
        return response()->download($certificate->file_path, "certificado_{$certificate->certificate_number}.pdf");
    }

    /**
     * Verifica a autenticidade de um certificado.
     */
    public function verify(string $code): View
    {
        $certificate = Certificate::where('verification_code', $code)
            ->with(['user', 'quiz', 'attempt'])
            ->first();
        
        return view('certificates.verify', compact('certificate'));
    }

    /**
     * Solicita um novo certificado.
     */
    public function request(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'module_id' => 'nullable|exists:modules,id',
            'quiz_id' => 'nullable|exists:quizzes,id',
        ]);
        
        if ($request->filled('module_id')) {
            $this->createModuleCertificate($user, $request->module_id);
        } elseif ($request->filled('quiz_id')) {
            $this->createQuizCertificate($user, $request->quiz_id);
        }
        
        return redirect()->route('certificates.index')
            ->with('success', 'Certificado solicitado com sucesso!');
    }
    
    /**
     * Obtém estatísticas de certificados do usuário.
     */
    private function getCertificateStats(User $user): array
    {
        $certificates = Certificate::where('user_id', $user->id)->get();
        
        $totalCertificates = $certificates->count();
        $validCertificates = $certificates->filter(function ($cert) {
            return $cert->isValid();
        })->count();
        
        $thisMonth = $certificates->filter(function ($cert) {
            return $cert->issued_at->isCurrentMonth();
        })->count();
        
        // Buscar categorias dos quizzes dos certificados
        $categories = Certificate::where('user_id', $user->id)
            ->whereNotNull('quiz_id')
            ->join('quizzes', 'certificates.quiz_id', '=', 'quizzes.id')
            ->pluck('quizzes.category')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        
        return [
            'total_certificates' => $totalCertificates,
            'valid_certificates' => $validCertificates,
            'this_month' => $thisMonth,
            'categories' => $categories
        ];
    }
    
    /**
     * Obtém módulos completados do usuário.
     */
    private function getCompletedModules(User $user): array
    {
        return UserProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('progress_percentage', 100)
            ->with('module')
            ->get()
            ->map(function ($progress) {
                return [
                    'id' => $progress->module->id,
                    'title' => $progress->module->title,
                    'category' => $progress->module->category,
                    'completed_at' => $progress->completed_at
                ];
            })
            ->toArray();
    }
    
    /**
     * Obtém quizzes aprovados do usuário.
     */
    private function getPassedQuizzes(User $user): array
    {
        return QuizAttempt::where('user_id', $user->id)
            ->where('completed_at', '!=', null)
            ->where('score', '>=', 70)
            ->with('quiz')
            ->get()
            ->map(function ($attempt) {
                return [
                    'id' => $attempt->quiz->id,
                    'title' => $attempt->quiz->title,
                    'score' => $attempt->score,
                    'completed_at' => $attempt->completed_at
                ];
            })
            ->toArray();
    }
    
    /**
     * Cria certificado para módulo.
     */
    private function createModuleCertificate(User $user, int $moduleId): void
    {
        $progress = UserProgress::where('user_id', $user->id)
            ->where('module_id', $moduleId)
            ->where('status', 'completed')
            ->with('module')
            ->first();
        
        if (!$progress) {
            throw new \Exception('Módulo não encontrado ou não concluído');
        }
        
        $certificateNumber = 'MOD-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . '-' . str_pad($moduleId, 4, '0', STR_PAD_LEFT);
        
        Certificate::create([
            'user_id' => $user->id,
            'quiz_id' => null,
            'quiz_attempt_id' => null,
            'certificate_number' => $certificateNumber,
            'title' => "Certificado de Conclusão - {$progress->module->title}",
            'description' => "Certificado de conclusão do módulo {$progress->module->title}",
            'issued_at' => now(),
            'valid_until' => now()->addYears(2),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'type' => 'module',
                'module_id' => $moduleId,
                'completion_date' => $progress->completed_at,
                'time_spent' => $progress->time_spent
            ]
        ]);
    }
    
    /**
     * Cria certificado para quiz.
     */
    private function createQuizCertificate(User $user, int $quizId): void
    {
        $attempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->where('completed_at', '!=', null)
            ->where('score', '>=', 70)
            ->with('quiz')
            ->orderBy('score', 'desc')
            ->first();
        
        if (!$attempt) {
            throw new \Exception('Quiz não encontrado ou não aprovado');
        }
        
        $certificateNumber = 'QUIZ-' . str_pad($user->id, 6, '0', STR_PAD_LEFT) . '-' . str_pad($quizId, 4, '0', STR_PAD_LEFT);
        
        Certificate::create([
            'user_id' => $user->id,
            'quiz_id' => $quizId,
            'quiz_attempt_id' => $attempt->id,
            'certificate_number' => $certificateNumber,
            'title' => "Certificado de Aprovação - {$attempt->quiz->title}",
            'description' => "Certificado de aprovação no quiz {$attempt->quiz->title}",
            'issued_at' => now(),
            'valid_until' => now()->addYears(2),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'type' => 'quiz',
                'quiz_id' => $quizId,
                'score' => $attempt->score,
                'completion_date' => $attempt->completed_at,
                'time_spent' => $attempt->time_spent
            ]
        ]);
    }
    
    /**
     * Gera código de verificação único.
     */
    private function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (Certificate::where('verification_code', $code)->exists());
        
        return $code;
    }
    
    /**
     * Gera arquivo PDF do certificado.
     */
    private function generateCertificateFile(Certificate $certificate): Certificate
    {
        // Implementar geração de PDF
        // Por enquanto, apenas simular
        $filePath = storage_path("app/certificates/certificate_{$certificate->id}.pdf");
        
        // Criar diretório se não existe
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        
        // Simular arquivo (em produção, gerar PDF real)
        file_put_contents($filePath, "Certificado {$certificate->certificate_number}");
        
        $certificate->update(['file_path' => $filePath]);
        
        return $certificate;
    }
}