<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Simulado;
use App\Models\SimuladoTentativa;
use App\Contracts\GamificationServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestSimuladoGamification extends Command
{
    protected $signature = 'test:simulado-gamification {user_id?}';
    protected $description = 'Testar integração da gamificação com simulados';

    public function __construct(private GamificationServiceInterface $gamificationService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $userId = $this->argument('user_id');
        
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

        $this->info("🧪 Testando Gamificação de Simulados para: {$user->name}");
        $this->newLine();

        // Verificar simulados disponíveis
        $simulados = Simulado::available()->get();
        if ($simulados->isEmpty()) {
            $this->error("Nenhum simulado disponível encontrado.");
            return 1;
        }

        $this->info("📋 Simulados disponíveis:");
        foreach ($simulados as $simulado) {
            $this->line("  - {$simulado->titulo} (ID: {$simulado->id})");
        }
        $this->newLine();

        // Escolher um simulado para teste
        $simulado = $simulados->first();
        $this->info("🎯 Testando simulado: {$simulado->titulo}");

        // Verificar estatísticas atuais
        $this->info("📊 Estatísticas atuais do usuário:");
        $stats = $this->gamificationService->getUserStats($user);
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Pontos Totais', $stats['total_points'] ?? 0],
                ['Nível Atual', $stats['current_level'] ?? 'Iniciante'],
                ['Simulados Completados', $stats['simulados_completados'] ?? 0],
                ['Simulados Aprovados', $stats['simulados_aprovados'] ?? 0],
            ]
        );

        // Simular conclusão de simulado
        $this->info("🎮 Simulando conclusão de simulado...");
        
        try {
            DB::beginTransaction();

            // Criar tentativa
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

            // Calcular pontos de gamificação
            $pontosBase = $simulado->pontos_recompensa;
            $pontosBonus = 0;
            $motivosBonus = [];

            // Bônus por performance
            if ($tentativa->score >= 90) {
                $pontosBonus += 50;
                $motivosBonus[] = 'Excelente performance (+50 pontos)';
            } elseif ($tentativa->score >= 80) {
                $pontosBonus += 25;
                $motivosBonus[] = 'Boa performance (+25 pontos)';
            }

            // Bônus por tempo
            $tempoMedio = $simulado->tempo_estimado * 60;
            if ($tentativa->tempo_gasto <= $tempoMedio * 0.7) {
                $pontosBonus += 30;
                $motivosBonus[] = 'Completou rapidamente (+30 pontos)';
            }

            // Bônus por primeira tentativa
            $tentativasAnteriores = SimuladoTentativa::where('user_id', $user->id)
                ->where('simulado_id', $simulado->id)
                ->where('id', '!=', $tentativa->id)
                ->count();
            
            if ($tentativasAnteriores === 0) {
                $pontosBonus += 20;
                $motivosBonus[] = 'Primeira tentativa (+20 pontos)';
            }

            $pontosTotais = $pontosBase + $pontosBonus;

            // Adicionar pontos
            $this->gamificationService->addPoints($user, $pontosTotais, 'simulado_aprovado', [
                'simulado_id' => $simulado->id,
                'tentativa_id' => $tentativa->id,
                'score' => $tentativa->score,
                'pontos_base' => $pontosBase,
                'pontos_bonus' => $pontosBonus,
                'motivos_bonus' => $motivosBonus
            ]);

            DB::commit();

            $this->info("✅ Simulado concluído com sucesso!");
            $this->newLine();

            // Mostrar resultados
            $this->info("🏆 Resultados da Gamificação:");
            $this->table(
                ['Item', 'Valor'],
                [
                    ['Score', "{$tentativa->score}%"],
                    ['Pontos Base', "+{$pontosBase}"],
                    ['Pontos Bônus', "+{$pontosBonus}"],
                    ['Total Ganho', "+{$pontosTotais}"],
                    ['Tempo Gasto', gmdate('H:i:s', $tentativa->tempo_gasto)],
                ]
            );

            if (!empty($motivosBonus)) {
                $this->info("🎁 Motivos dos Bônus:");
                foreach ($motivosBonus as $motivo) {
                    $this->line("  ✓ {$motivo}");
                }
                $this->newLine();
            }

            // Verificar estatísticas atualizadas
            $this->info("📈 Estatísticas atualizadas:");
            $statsAtualizadas = $this->gamificationService->getUserStats($user);
            $this->table(
                ['Métrica', 'Antes', 'Depois'],
                [
                    ['Pontos Totais', $stats['total_points'] ?? 0, $statsAtualizadas['total_points'] ?? 0],
                    ['Nível Atual', $stats['current_level'] ?? 'Iniciante', $statsAtualizadas['current_level'] ?? 'Iniciante'],
                    ['Simulados Completados', $stats['simulados_completados'] ?? 0, $statsAtualizadas['simulados_completados'] ?? 0],
                    ['Simulados Aprovados', $stats['simulados_aprovados'] ?? 0, $statsAtualizadas['simulados_aprovados'] ?? 0],
                ]
            );

            // Verificar se subiu de nível
            if (($stats['current_level'] ?? 'Iniciante') !== ($statsAtualizadas['current_level'] ?? 'Iniciante')) {
                $this->info("🎉 PARABÉNS! O usuário subiu de nível!");
                $this->info("   De: {$stats['current_level']} → Para: {$statsAtualizadas['current_level']}");
            }

            $this->info("🎯 Teste concluído com sucesso!");
            $this->info("   Acesse: http://127.0.0.1:8000/simulados para ver os resultados");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Erro durante o teste: {$e->getMessage()}");
            return 1;
        }

        return 0;
    }
} 