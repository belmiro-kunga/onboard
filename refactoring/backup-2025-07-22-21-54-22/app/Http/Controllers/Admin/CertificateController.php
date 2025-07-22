<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CertificateController extends Controller
{
    /**
     * Exibe a lista de certificados.
     */
    public function index(Request $request): View
    {
        $query = Certificate::with(['user', 'module']);
        
        // Filtros
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('module_id')) {
            $query->where('module_id', $request->module_id);
        }
        
        if ($request->has('reference_type')) {
            $query->where('reference_type', $request->reference_type);
        }
        
        // Busca por nome de usuário
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Ordenação
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);
        
        // Paginação
        $certificates = $query->paginate(15);
        
        // Dados para filtros
        $users = User::select('id', 'name')->orderBy('name')->get();
        $modules = Module::select('id', 'title')->orderBy('title')->get();
        
        return view('admin.certificates.index', compact('certificates', 'users', 'modules'));
    }

    /**
     * Exibe o formulário para criar um novo certificado.
     */
    public function create(): View
    {
        $users = User::select('id', 'name', 'email')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $modules = Module::select('id', 'title')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();
            
        return view('admin.certificates.create', compact('users', 'modules'));
    }

    /**
     * Armazena um novo certificado.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
            'reference_type' => 'required|string|in:module,course,simulado',
            'reference_id' => 'nullable|integer',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'custom_title' => 'nullable|string|max:255',
            'custom_description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Gerar código único
        $code = Str::random(10);
        while (Certificate::where('code', $code)->exists()) {
            $code = Str::random(10);
        }
        
        // Criar o certificado
        $certificate = Certificate::create([
            'user_id' => $request->user_id,
            'module_id' => $request->module_id,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
            'code' => $code,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'custom_title' => $request->custom_title,
            'custom_description' => $request->custom_description,
        ]);
        
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificado criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um certificado.
     */
    public function show(Certificate $certificate): View
    {
        $certificate->load(['user', 'module']);
        
        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Exibe o formulário para editar um certificado.
     */
    public function edit(Certificate $certificate): View
    {
        $certificate->load(['user', 'module']);
        
        $users = User::select('id', 'name', 'email')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
            
        $modules = Module::select('id', 'title')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();
            
        return view('admin.certificates.edit', compact('certificate', 'users', 'modules'));
    }

    /**
     * Atualiza um certificado.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'module_id' => 'required|exists:modules,id',
            'reference_type' => 'required|string|in:module,course,simulado',
            'reference_id' => 'nullable|integer',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'custom_title' => 'nullable|string|max:255',
            'custom_description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Atualizar o certificado
        $certificate->update([
            'user_id' => $request->user_id,
            'module_id' => $request->module_id,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'custom_title' => $request->custom_title,
            'custom_description' => $request->custom_description,
        ]);
        
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certificado atualizado com sucesso!');
    }

    /**
     * Remove um certificado.
     */
    public function destroy(Certificate $certificate)
    {
        // Remover arquivo PDF se existir
        if ($certificate->file_path) {
            Storage::disk('public')->delete('certificates/' . $certificate->file_path);
        }
        
        // Excluir o certificado
        $certificate->delete();
        
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificado excluído com sucesso!');
    }
    
    /**
     * Gera o PDF do certificado.
     */
    public function generatePdf(Certificate $certificate)
    {
        $certificate->load(['user', 'module']);
        
        // Gerar o PDF usando o serviço de certificados
        $pdfService = app(\App\Services\CertificateService::class);
        $pdf = $pdfService->generatePdf($certificate);
        
        // Salvar o PDF
        $filename = 'certificate_' . $certificate->code . '.pdf';
        $pdf->save(storage_path('app/public/certificates/' . $filename));
        
        // Atualizar o caminho do arquivo no certificado
        $certificate->file_path = $filename;
        $certificate->save();
        
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'PDF do certificado gerado com sucesso!');
    }
    
    /**
     * Baixa o PDF do certificado.
     */
    public function downloadPdf(Certificate $certificate)
    {
        if (!$certificate->file_path) {
            return redirect()->back()
                ->with('error', 'O PDF deste certificado ainda não foi gerado.');
        }
        
        $filePath = storage_path('app/public/certificates/' . $certificate->file_path);
        
        if (!file_exists($filePath)) {
            return redirect()->back()
                ->with('error', 'O arquivo do certificado não foi encontrado.');
        }
        
        return response()->download($filePath, 'certificate_' . $certificate->code . '.pdf');
    }
    
    /**
     * Envia o certificado por e-mail para o usuário.
     */
    public function sendByEmail(Certificate $certificate)
    {
        $certificate->load('user');
        
        // Verificar se o PDF foi gerado
        if (!$certificate->file_path) {
            return redirect()->back()
                ->with('error', 'O PDF deste certificado ainda não foi gerado.');
        }
        
        // Enviar e-mail com o certificado
        try {
            \Mail::to($certificate->user->email)
                ->send(new \App\Mail\CertificateGenerated($certificate));
                
            return redirect()->back()
                ->with('success', 'Certificado enviado por e-mail com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao enviar o certificado por e-mail: ' . $e->getMessage());
        }
    }
    
    /**
     * Verifica a autenticidade de um certificado pelo código.
     */
    public function verify(Request $request): View
    {
        $code = $request->get('code');
        $certificate = null;
        $isValid = false;
        $isExpired = false;
        
        if ($code) {
            $certificate = Certificate::with(['user', 'module'])
                ->where('code', $code)
                ->first();
                
            if ($certificate) {
                $isValid = true;
                
                // Verificar se está expirado
                if ($certificate->expiry_date && now()->gt($certificate->expiry_date)) {
                    $isExpired = true;
                }
            }
        }
        
        return view('admin.certificates.verify', compact('certificate', 'isValid', 'isExpired', 'code'));
    }
}