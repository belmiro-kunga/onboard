<?php

declare(strict_types=1);

namespace App\Contracts;

interface PDFGenerationServiceInterface
{
    /**
     * Gera PDF genérico
     */
    public function generatePDF(string $content, string $filename): bool;

    /**
     * Gera PDF de certificado
     */
    public function generateCertificatePDF(object $certificate): bool;

    /**
     * Gera PDF de relatório
     */
    public function generateReportPDF(string $title, array $data): bool;

    /**
     * Valida PDF
     */
    public function validatePDF(string $filename): bool;

    /**
     * Obtém tamanho do PDF
     */
    public function getPDFSize(string $filename): int;
} 