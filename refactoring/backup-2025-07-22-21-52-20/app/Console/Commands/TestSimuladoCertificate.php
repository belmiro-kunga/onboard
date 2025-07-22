<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Simulado;
use App\Models\SimuladoTentativa;
use App\Models\Certificate;
use App\Contracts\CertificateServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestSimuladoCertificate extends Command
{
    protected $signature = 'test:simulado-certificate {user_id?} {simulado_id?}';
    protected $description = 'Testar geração de certificados de simulados';

    public function __construct(private CertificateServiceInterface $certificateService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        $simuladoId = $this->argument('simulado_id');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuário com ID {$userId} não encontrado.");
                return 1;
            }
        } else {
            $user = User::first();
            if (!$user) {
                $this->error("Nenhum usuário encontrado no sistema.");
                return 1;
            }
        }

        $this->info("🏆 Testando Geração de Certificados de Simulados para: {$user->name}");
        $this->newLine();

        // Verificar simulados disponíveis
        $simulados = Simulado::available()->get();
        if ($simulados->isEmpty()) {
            $this->error("Nenhum simulado disponível encontrado.");
            return 1;
        }

        // Escolher simulado
        if ($simuladoId) {
            $simulado = Simulado::find($simuladoId);
            if (!$simulado) {
                $this->error("Simulado com ID {$simuladoId} não encontrado.");
                return 1;
            }
        } else {
            $simulado = $simulados->first();
        }

        $this->info("📋 Simulado selecionado: {$simulado->titulo}");
        $this->newLine();

        // Verificar certificados existentes
        $existingCertificates = Certificate::where('user_id', $user->id)
            ->where('type', 'simulado')
            ->where('reference_id', $simulado->id)
            ->count();

        $this->info("📊 Certificados existentes para este simulado: {$existingCertificates}");
        $this->newLine();

        // Verificar tentativas existentes
        $tentativas = SimuladoTentativa::where('user_id', $user->id)
            ->where('simulado_id', $simulado->id)
            ->where('status', 'completed')
            ->get();

        if ($tentativas->isEmpty()) {
            $this->info("🔄 Criando tentativa de teste...");
            
            try {
                DB::beginTransaction();

                // Criar tentativa aprovada
                $tentativa = SimuladoTentativa::create([
                    'user_id' => $user->id,
                    'simulado_id' => $simulado->id,
                    'status' => 'completed',
                    'score' => 85, // Score de teste
                    'tempo_gasto' => 1800, // 30 minutos
                    'iniciado_em' => now()->subMinutes(30),
                    'finalizado_em' => now(),
                ]);

                // Simular respostas corretas
                $questoes = $simulado->questoesAtivas;
                $respostasCorretas = round(count($questoes) * 0.85); // 85% de acerto
                
                foreach ($questoes as $index => $questao) {
                    $correta = $index < $respostasCorretas;
                    $tentativa->respostas()->create([
                        'questao_id' => $questao->id,
                        'resposta_selecionada' => $correta ? $questao->resposta_correta : 'A',
                        'correta' => $correta,
                        'tempo_resposta' => rand(30, 120),
                    ]);
                }

                DB::commit();
                $this->info("✅ Tentativa criada com sucesso!");

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Erro ao criar tentativa: {$e->getMessage()}");
                return 1;
            }
        } else {
            $tentativa = $tentativas->first();
            $this->info("✅ Usando tentativa existente (ID: {$tentativa->id})");
        }

        $this->newLine();

        // Testar geração de certificado
        $this->info("🎓 Testando geração de certificado...");
        
        try {
            $certificate = $this->certificateService->generateSimuladoCertificate($user, $simulado, $tentativa);
            
            if ($certificate) {
                $this->info("✅ Certificado gerado com sucesso!");
                $this->newLine();
                
                $this->info("📋 Detalhes do Certificado:");
                $this->table(
                    ['Campo', 'Valor'],
                    [
                        ['ID', $certificate->id],
                        ['Título', $certificate->title],
                        ['Tipo', $certificate->type],
                        ['Código de Verificação', $certificate->verification_code],
                        ['Emitido em', $certificate->issued_at ? $certificate->issued_at->format('d/m/Y H:i:s') : 'N/A'],
                        ['Expira em', $certificate->expires_at ? $certificate->expires_at->format('d/m/Y H:i:s') : 'N/A'],
                        ['Score', $certificate->metadata['score'] ?? 'N/A'],
                        ['Tempo de Conclusão', $certificate->metadata['completion_time_formatted'] ?? 'N/A'],
                        ['Pontos Totais', $certificate->metadata['pontos_totais'] ?? 'N/A'],
                        ['Nível de Performance', $certificate->metadata['performance_level'] ?? 'N/A'],
                    ]
                );
                
                $this->newLine();
                
                // Verificar certificados totais do usuário
                $totalCertificates = Certificate::where('user_id', $user->id)->count();
                $simuladoCertificates = Certificate::where('user_id', $user->id)
                    ->where('type', 'simulado')
                    ->count();
                
                $this->info("📊 Estatísticas Atualizadas:");
                $this->table(
                    ['Métrica', 'Valor'],
                    [
                        ['Total de Certificados', $totalCertificates],
                        ['Certificados de Simulados', $simuladoCertificates],
                        ['Certificados Outros Tipos', $totalCertificates - $simuladoCertificates],
                    ]
                );
                
                $this->newLine();
                $this->info("🎯 Certificado disponível em: http://127.0.0.1:8000/certificates");
                $this->info("🔍 Verificar certificado: http://127.0.0.1:8000/certificates/verify/{$certificate->verification_code}");
                
            } else {
                $this->error("❌ Falha ao gerar certificado.");
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("❌ Erro ao gerar certificado: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
} 