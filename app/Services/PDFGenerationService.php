<?php

namespace App\Services;

use App\Contracts\PDFGenerationServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;
use Dompdf\Options;

class PDFGenerationService implements PDFGenerationServiceInterface
{
    private Dompdf $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
        $this->configureDompdf();
    }

    /**
     * Gerar PDF a partir de HTML.
     */
    public function generatePDF(string $html, string $filename): bool
    {
        try {
            $this->dompdf->loadHtml($html);
            $this->dompdf->setPaper('A4', 'landscape');
            $this->dompdf->render();
            
            $pdfContent = $this->dompdf->output();
            
            Storage::disk('public')->put($filename, $pdfContent);
            
            return true;
        } catch (\Exception $e) {
            Log::error("Erro ao gerar PDF {$filename}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Gerar PDF com template personalizado.
     */
    public function generatePDFWithTemplate(string $template, array $data, string $filename): bool
    {
        try {
            $html = view($template, $data)->render();
            return $this->generatePDF($html, $filename);
        } catch (\Exception $e) {
            Log::error("Erro ao gerar PDF com template {$template}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Configurar DomPDF.
     */
    private function configureDompdf(): void
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $this->dompdf->setOptions($options);
    }

    /**
     * Verificar se arquivo PDF existe.
     */
    public function pdfExists(string $filename): bool
    {
        return Storage::disk('public')->exists($filename);
    }

    /**
     * Obter URL do PDF.
     */
    public function getPDFUrl(string $filename): ?string
    {
        if ($this->pdfExists($filename)) {
            return Storage::disk('public')->url($filename);
        }
        
        return null;
    }

    /**
     * Excluir PDF.
     */
    public function deletePDF(string $filename): bool
    {
        try {
            if ($this->pdfExists($filename)) {
                Storage::disk('public')->delete($filename);
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error("Erro ao excluir PDF {$filename}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Gera PDF de certificado
     */
    public function generateCertificatePDF(object $certificate): bool
    {
        try {
            $html = $this->generateCertificateHTML($certificate);
            $filename = "certificates/certificate_{$certificate->id}.pdf";
            return $this->generatePDF($html, $filename);
        } catch (\Exception $e) {
            Log::error("Erro ao gerar PDF de certificado: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Gera PDF de relatório
     */
    public function generateReportPDF(string $title, array $data): bool
    {
        try {
            $html = view('pdf.report', ['title' => $title, 'data' => $data])->render();
            $filename = "reports/report_" . time() . ".pdf";
            return $this->generatePDF($html, $filename);
        } catch (\Exception $e) {
            Log::error("Erro ao gerar PDF de relatório: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Valida PDF
     */
    public function validatePDF(string $filename): bool
    {
        return $this->pdfExists($filename);
    }

    /**
     * Obtém tamanho do PDF
     */
    public function getPDFSize(string $filename): int
    {
        if ($this->pdfExists($filename)) {
            return Storage::disk('public')->size($filename);
        }
        return 0;
    }

    /**
     * Gera HTML do certificado
     */
    private function generateCertificateHTML(object $certificate): string
    {
        return view('pdf.certificate', ['certificate' => $certificate])->render();
    }
} 