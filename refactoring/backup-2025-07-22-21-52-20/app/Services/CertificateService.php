<?php

namespace App\Services;

use App\Models\User;
use App\Models\Module;
use App\Models\Certificate;
use App\Models\UserProgress;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Contracts\NotificationServiceInterface;
use App\Contracts\PDFGenerationServiceInterface;
use App\Contracts\CertificateServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CertificateService implements CertificateServiceInterface
{
    protected $notificationService;
    protected $pdfService;

    public function __construct(
        NotificationServiceInterface $notificationService,
        PDFGenerationServiceInterface $pdfService
    ) {
        $this->notificationService = $notificationService;
        $this->pdfService = $pdfService;
    }

    /**
     * Verificar e gerar certificados automaticamente
     */
    public function checkAndGenerateAutomaticCertificates(User $user): array
    {
        $generatedCertificates = [];
        
        // Verificar certificados por módulo
        $moduleCompletions = $this->checkModuleCompletions($user);
        foreach ($moduleCompletions as $completion) {
            $certificate = $this->generateModuleCertificate($user, $completion['module']);
            if ($certificate) {
                $generatedCertificates[] = $certificate;
            }
        }
        
        // Verificar certificados por categoria
        $categoryCompletions = $this->checkCategoryCompletions($user);
        foreach ($categoryCompletions as $completion) {
            $certificate = $this->generateCategoryCertificate($user, $completion['category'], $completion['modules']);
            if ($certificate) {
                $generatedCertificates[] = $certificate;
            }
        }
        
        // Verificar certificado geral
        if ($this->checkOverallCompletion($user)) {
            $certificate = $this->generateOverallCertificate($user);
            if ($certificate) {
                $generatedCertificates[] = $certificate;
            }
        }
        
        // Verificar certificados especiais
        $specialCertificates = $this->checkSpecialCertificates($user);
        $generatedCertificates = array_merge($generatedCertificates, $specialCertificates);
        
        return $generatedCertificates;
    }

    /**
     * Verificar conclusões de módulos
     */
    private function checkModuleCompletions(User $user): array
    {
        $completions = [];
        
        $completedModules = UserProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();
        
        foreach ($completedModules as $progress) {
            $module = Module::find($progress->module_id);
            
            // Verificar se já existe certificado
            $existingCertificate = Certificate::where('user_id', $user->id)
                ->where('type', 'module')
                ->where('reference_id', $module->id)
                ->first();
            
            if (!$existingCertificate && $this->moduleQualifiesForCertificate($module)) {
                $completions[] = [
                    'module' => $module,
                    'progress' => $progress
                ];
            }
        }
        
        return $completions;
    }

    /**
     * Verificar se o módulo qualifica para certificado
     */
    private function moduleQualifiesForCertificate(Module $module): bool
    {
        // Verificar se o módulo tem quiz associado
        $moduleQuizzes = Quiz::where('module_id', $module->id)->get();
        
        if ($moduleQuizzes->isNotEmpty()) {
            foreach ($moduleQuizzes as $quiz) {
                $bestAttempt = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('passed', true)
                    ->first();
                
                if (!$bestAttempt) {
                    return false;
                }
            }
        }
        
        return true;
    }

    /**
     * Verificar conclusões por categoria
     */
    private function checkCategoryCompletions(User $user): array
    {
        $completions = [];
        $categories = Module::select('category')->distinct()->pluck('category');
        
        foreach ($categories as $category) {
            $categoryModules = Module::where('category', $category)
                ->where('is_active', true)
                ->get();
            
            $completedModules = UserProgress::where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereIn('module_id', $categoryModules->pluck('id'))
                ->count();
            
            // Verificar se completou todos os módulos da categoria
            if ($completedModules == $categoryModules->count() && $categoryModules->count() > 0) {
                // Verificar se já existe certificado
                $existingCertificate = Certificate::where('user_id', $user->id)
                    ->where('type', 'category')
                    ->where('category', $category)
                    ->first();
                
                if (!$existingCertificate) {
                    $completions[] = [
                        'category' => $category,
                        'modules' => $categoryModules
                    ];
                }
            }
        }
        
        return $completions;
    }

    /**
     * Verificar conclusão geral
     */
    private function checkOverallCompletion(User $user): bool
    {
        $totalModules = Module::where('is_active', true)->count();
        $completedModules = UserProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        
        // Verificar se já existe certificado geral
        $existingCertificate = Certificate::where('user_id', $user->id)
            ->where('type', 'overall')
            ->first();
        
        return !$existingCertificate && $completedModules == $totalModules && $totalModules > 0;
    }

    /**
     * Verificar certificados especiais
     */
    private function checkSpecialCertificates(User $user): array
    {
        $certificates = [];
        
        // Certificado de Excelência (média > 90%)
        $avgScore = QuizAttempt::where('user_id', $user->id)
            ->where('passed', true)
            ->avg('score');
        
        if ($avgScore >= 90) {
            $existingCertificate = Certificate::where('user_id', $user->id)
                ->where('type', 'excellence')
                ->first();
            
            if (!$existingCertificate) {
                $certificate = $this->generateExcellenceCertificate($user, $avgScore);
                if ($certificate) {
                    $certificates[] = $certificate;
                }
            }
        }
        
        // Certificado de Velocidade
        if ($this->checkSpeedCompletion($user)) {
            $existingCertificate = Certificate::where('user_id', $user->id)
                ->where('type', 'speed')
                ->first();
            
            if (!$existingCertificate) {
                $certificate = $this->generateSpeedCertificate($user);
                if ($certificate) {
                    $certificates[] = $certificate;
                }
            }
        }
        
        return $certificates;
    }

    /**
     * Gerar certificado de quiz
     * 
     * @param User $user Usuário que receberá o certificado
     * @param Quiz $quiz Quiz aprovado
     * @param QuizAttempt $attempt Tentativa aprovada
     * @return Certificate|null Certificado gerado ou null se falha
     */
    public function generateQuizCertificate(User $user, Quiz $quiz, QuizAttempt $attempt): ?Certificate
    {
        // Verificar se a tentativa pertence ao usuário e ao quiz
        if ($attempt->user_id != $user->id || $attempt->quiz_id != $quiz->id) {
            return null;
        }
        
        // Verificar se a tentativa foi aprovada
        if (!$attempt->passed) {
            return null;
        }
        
        // Gerar número de certificado
        $certificateNumber = $this->generateCertificateNumber();
        
        $certificateData = [
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'type' => 'quiz',
            'title' => "Certificado - {$quiz->title}",
            'description' => "Certificado de aprovação no quiz {$quiz->title}",
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'quiz_title' => $quiz->title,
                'quiz_category' => $quiz->category,
                'score' => $attempt->score,
                'completion_date' => $attempt->completed_at,
                'points_earned' => $quiz->points_reward
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Gerar certificado de módulo
     */
    public function generateModuleCertificate(User $user, Module $module): ?Certificate
    {
        $certificateData = [
            'user_id' => $user->id,
            'type' => 'module',
            'title' => "Certificado de Conclusão - {$module->title}",
            'description' => "Certificado de conclusão do módulo {$module->title}",
            'reference_id' => $module->id,
            'category' => $module->category,
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'module_title' => $module->title,
                'module_category' => $module->category,
                'completion_date' => now()->toDateString(),
                'points_earned' => $module->points_reward
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Gerar certificado de categoria
     */
    private function generateCategoryCertificate(User $user, string $category, $modules): ?Certificate
    {
        $categoryNames = [
            'culture' => 'Cultura Organizacional',
            'hr' => 'Recursos Humanos',
            'security' => 'Segurança da Informação',
            'it' => 'Tecnologia',
            'processes' => 'Processos e Procedimentos',
        ];
        
        $categoryName = $categoryNames[$category] ?? $category;
        
        $certificateData = [
            'user_id' => $user->id,
            'type' => 'category',
            'title' => "Certificado de Categoria - {$categoryName}",
            'description' => "Certificado de conclusão da categoria {$categoryName}",
            'category' => $category,
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'category_name' => $categoryName,
                'modules_count' => $modules->count(),
                'total_points' => $modules->sum('points_reward'),
                'completion_date' => now()->toDateString(),
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Gerar certificado geral
     */
    private function generateOverallCertificate(User $user): ?Certificate
    {
        $totalPoints = Module::where('is_active', true)->sum('points_reward');
        $totalModules = Module::where('is_active', true)->count();
        
        $certificateData = [
            'user_id' => $user->id,
            'type' => 'overall',
            'title' => 'Certificado de Conclusão do Programa',
            'description' => 'Certificado de conclusão de todo o programa de treinamento',
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'total_points' => $totalPoints,
                'total_modules' => $totalModules,
                'program_name' => 'Programa de Onboarding',
                'completion_date' => now()->toDateString(),
                'achievement' => 'Conclusão Total do Programa',
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Gerar certificado de excelência
     */
    private function generateExcellenceCertificate(User $user, float $avgScore): ?Certificate
    {
        $certificateData = [
            'user_id' => $user->id,
            'type' => 'excellence',
            'title' => 'Certificado de Excelência',
            'description' => 'Certificado de reconhecimento por excelência acadêmica',
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'achievement_type' => 'Excelência Acadêmica',
                'average_score' => round($avgScore, 2),
                'recognition_level' => 'Excelência',
                'completion_date' => now()->toDateString(),
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação especial
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Gerar certificado de velocidade
     */
    private function generateSpeedCertificate(User $user): ?Certificate
    {
        $certificateData = [
            'user_id' => $user->id,
            'type' => 'speed',
            'title' => 'Certificado de Aprendizado Acelerado',
            'description' => 'Certificado de reconhecimento por conclusão rápida do programa',
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'achievement_type' => 'Aprendizado Acelerado',
                'recognition_level' => 'Velocidade',
                'completion_date' => now()->toDateString(),
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Gerar certificado para simulado
     * 
     * @param User $user Usuário que receberá o certificado
     * @param $simulado Simulado aprovado
     * @param $tentativa Tentativa aprovada
     * @return Certificate|null Certificado gerado ou null se falha
     */
    public function generateSimuladoCertificate(User $user, $simulado, $tentativa): ?Certificate
    {
        // Verificar se a tentativa pertence ao usuário e ao simulado
        if ($tentativa->user_id != $user->id || $tentativa->simulado_id != $simulado->id) {
            return null;
        }
        
        // Verificar se a tentativa foi aprovada
        if ($tentativa->score < $simulado->nota_aprovacao) {
            return null;
        }
        
        // Gerar número de certificado
        $certificateNumber = $this->generateCertificateNumber();
        
        $certificateData = [
            'user_id' => $user->id,
            'type' => 'simulado',
            'title' => "Certificado - {$simulado->titulo}",
            'description' => "Certificado de aprovação no simulado {$simulado->titulo}",
            'category' => $simulado->categoria,
            'reference_id' => $simulado->id,
            'certificate_number' => $certificateNumber,
            'issued_at' => now(),
            'verification_code' => $this->generateVerificationCode(),
            'metadata' => [
                'simulado_titulo' => $simulado->titulo,
                'simulado_categoria' => $simulado->categoria,
                'simulado_descricao' => $simulado->descricao,
                'score' => $tentativa->score,
                'data_conclusao' => $tentativa->completed_at,
                'pontos_recompensa' => $simulado->pontos_recompensa,
            ]
        ];
        
        $certificate = Certificate::create($certificateData);
        
        // Gerar PDF do certificado
        $this->generateCertificatePDF($certificate);
        
        // Enviar notificação
        $this->notificationService->sendCertificateNotification($user, $certificate);
        
        return $certificate;
    }

    /**
     * Verificar conclusão rápida
     */
    private function checkSpeedCompletion(User $user): bool
    {
        $firstProgress = UserProgress::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->first();
        
        $lastProgress = UserProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if (!$firstProgress || !$lastProgress) {
            return false;
        }
        
        $totalDays = Carbon::parse($firstProgress->created_at)->diffInDays($lastProgress->updated_at);
        $totalModules = Module::where('is_active', true)->count();
        
        // Verificar se completou em menos dias do que o número de módulos
        return $totalDays < $totalModules;
    }

    /**
     * Gerar código de verificação único
     */
    private function generateVerificationCode(): string
    {
        return 'HCP-' . strtoupper(Str::random(8)) . '-' . date('Y');
    }

    /**
     * Gerar PDF do certificado
     */
    public function generateCertificatePDF(Certificate $certificate)
    {
        // Gerar HTML do certificado
        $html = $this->generateCertificateHTML($certificate);
        
        // Usar serviço de PDF para gerar o arquivo
        $pdfContent = $this->pdfService->generatePDF($html);
        
        $filename = "certificate_{$certificate->id}_{$certificate->verification_code}.pdf";
        
        // Salvar o arquivo (simulado)
        Storage::disk('public')->put("certificates/{$filename}", $pdfContent);
        
        // Atualizar certificado com caminho do arquivo
        $certificate->update([
            'file_path' => "certificates/{$filename}",
            'file_size' => strlen($pdfContent),
        ]);
    }

    /**
     * Gerar HTML do certificado
     */
    private function generateCertificateHTML(Certificate $certificate): string
    {
        $user = $certificate->user;
        $issuedDate = Carbon::parse($certificate->issued_at)->format('d/m/Y');

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Certificado - {$certificate->title}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                .certificate { border: 5px solid #1a5276; padding: 20px; margin: 20px; text-align: center; }
                .title { font-size: 24px; margin-bottom: 30px; color: #1a5276; }
                .content { font-size: 18px; margin: 20px 0; line-height: 1.5; }
                .footer { margin-top: 40px; font-size: 14px; color: #666; }
            </style>
        </head>
        <body>
            <div class='certificate'>
                <div class='title'>
                    <h1>{$certificate->title}</h1>
                </div>
                
                <div class='content'>
                    Certificamos que <strong>{$user->name}</strong>
                    <br><br>
                    {$certificate->description}
                </div>
                
                <div class='footer'>
                    <p>Emitido em: {$issuedDate}</p>
                    <p>Departamento: {$user->department}</p>
                </div>
                
                <div class='verification'>
                    Código de Verificação: {$certificate->verification_code}
                    <br>
                    Verifique a autenticidade em: https://onboarding.empresa.com.br/verificar-certificado
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Verificar certificado por código
     */
    public function verifyCertificate(string $code): ?Certificate
    {
        return Certificate::where('verification_code', $code)
            ->with('user')
            ->first();
    }

    /**
     * Revogar certificado
     */
    public function revokeCertificate(int $certificateId): bool
    {
        $certificate = Certificate::find($certificateId);
        
        if (!$certificate) {
            return false;
        }
        
        $certificate->update([
            'revoked' => true,
            'revoked_at' => now()
        ]);
        
        return true;
    }

    /**
     * Obter certificados do usuário
     */
    public function getUserCertificates(User $user): array
    {
        $certificates = Certificate::where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->get();
            
        return [
            'total' => $certificates->count(),
            'valid' => $certificates->where('revoked', false)->count(),
            'certificates' => $certificates
        ];
    }

    /**
     * Obter nível de desempenho baseado no score
     */
    private function getPerformanceLevel(int $score): string
    {
        if ($score >= 95) return 'excelente';
        if ($score >= 85) return 'muito_bom';
        if ($score >= 75) return 'bom';
        if ($score >= 70) return 'satisfatorio';
        return 'insatisfatorio';
    }
    
    /**
     * Gera relatório de certificados
     */
    public function generateCertificateReport(): array
    {
        $totalCertificates = Certificate::count();
        $validCertificates = Certificate::where('revoked', false)->count();
        $revokedCertificates = Certificate::where('revoked', true)->count();
        
        $certificatesByType = Certificate::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->get()
            ->pluck('total', 'type')
            ->toArray();
            
        $certificatesByMonth = Certificate::selectRaw('MONTH(issued_at) as month, YEAR(issued_at) as year, count(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->map(function($item) {
                $monthName = Carbon::create(null, $item->month)->format('F');
                return [
                    'month' => $monthName,
                    'year' => $item->year,
                    'period' => $monthName . ' ' . $item->year,
                    'total' => $item->total
                ];
            })
            ->toArray();
            
        $topUsers = Certificate::selectRaw('user_id, count(*) as total')
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'user_id' => $item->user_id,
                    'user_name' => $item->user->name ?? 'Usuário Desconhecido',
                    'user_email' => $item->user->email ?? '',
                    'total_certificates' => $item->total
                ];
            })
            ->toArray();
            
        return [
            'summary' => [
                'total' => $totalCertificates,
                'valid' => $validCertificates,
                'revoked' => $revokedCertificates,
            ],
            'by_type' => $certificatesByType,
            'by_month' => $certificatesByMonth,
            'top_users' => $topUsers
        ];
    }
}