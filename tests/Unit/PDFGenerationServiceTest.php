<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\PDFGenerationService;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PDFGenerationServiceTest extends TestCase
{
    public function test_generate_pdf_creates_file(): void
    {
        Storage::fake('public');
        $service = new PDFGenerationService();
        $html = '<h1>Test PDF</h1>';
        $filename = 'test/test.pdf';
        $result = $service->generatePDF($html, $filename);
        $this->assertTrue($result);
        Storage::disk('public')->assertExists($filename);
    }
} 