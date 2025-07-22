<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Certificate;
use App\Models\QuizAttempt;

interface CertificateServiceInterface
{
    /**
     * Gera certificado para módulo
     */
    public function generateModuleCertificate(User $user, Module $module): ?Certificate;

    /**
     * Gera certificado para quiz
     */
    public function generateQuizCertificate(User $user, Quiz $quiz, QuizAttempt $attempt): ?Certificate;

    /**
     * Verifica certificado por código
     */
    public function verifyCertificate(string $verificationCode): ?Certificate;

    /**
     * Revoga certificado
     */
    public function revokeCertificate(int $certificateId): bool;

    /**
     * Obtém certificados do usuário
     */
    public function getUserCertificates(User $user): array;

    /**
     * Verifica e gera certificados automáticos
     */
    public function checkAndGenerateAutomaticCertificates(User $user): array;

    /**
     * Gera relatório de certificados
     */
    public function generateCertificateReport(): array;
} 