<?php

namespace App\Listeners;

use App\Events\SimuladoCompleted;
use App\Contracts\GamificationServiceInterface;
use App\Contracts\NotificationServiceInterface;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SimuladoCompletedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private GamificationServiceInterface $gamificationService,
        private NotificationServiceInterface $notificationService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(SimuladoCompleted $event): void
    {
        $user = $event->user;
        $simulado = $event->simulado;
        $tentativa = $event->tentativa;
        $hasPassed = $tentativa->isPassed();

        try {
            // Verificar conquistas relacionadas a simulados
            $this->checkSimuladoAchievements($user, $simulado, $tentativa);
            
            // Verificar conquistas de performance
            if ($hasPassed) {
                $this->checkPerformanceAchievements($user, $tentativa);
            }
            
            // Verificar conquistas de participação
            $this->checkParticipationAchievements($user);
            
            // Atualizar estatísticas do usuário
            $this->updateUserStats($user);
            
        } catch (\Exception $e) {
            Log::error('Erro no SimuladoCompletedListener', [
                'user_id' => $user->id,
                'simulado_id' => $simulado->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verificar conquistas relacionadas a simulados
     */
    private function checkSimuladoAchievements($user, $simulado, $tentativa): void
    {
        // Conquista: Primeiro simulado aprovado
        if ($tentativa->isPassed()) {
            $totalAprovacoes = $user->simuladoTentativas()
                ->where('status', 'completed')
                ->where('score', '>=', '70')
                ->count();
                
            if ($totalAprovacoes === 1) {
                $this->awardAchievement($user, 'primeiro_simulado_aprovado', 'Primeiro Simulado Aprovado');
            }
        }
        
        // Conquista: 5 simulados aprovados
        $totalAprovacoes = $user->simuladoTentativas()
            ->where('status', 'completed')
            ->where('score', '>=', '70')
            ->count();
            
        if ($totalAprovacoes === 5) {
            $this->awardAchievement($user, 'cinco_simulados_aprovados', '5 Simulados Aprovados');
        }
        
        // Conquista: 10 simulados aprovados
        if ($totalAprovacoes === 10) {
            $this->awardAchievement($user, 'dez_simulados_aprovados', '10 Simulados Aprovados');
        }
        
        // Conquista: Simulado com 100% de acerto
        if ($tentativa->score === 100) {
            $this->awardAchievement($user, 'simulado_perfeito', 'Simulado Perfeito');
        }
    }

    /**
     * Verificar conquistas de performance
     */
    private function checkPerformanceAchievements($user, $tentativa): void
    {
        // Conquista: Score alto (90%+)
        if ($tentativa->score >= 90) {
            $this->awardAchievement($user, 'score_excelente', 'Score Excelente');
        }
        
        // Conquista: Tempo rápido
        $tempoMedio = 30 * 60; // 30 minutos em segundos
        if ($tentativa->tempo_gasto <= $tempoMedio * 0.6) {
            $this->awardAchievement($user, 'tempo_rapido', 'Tempo Rápido');
        }
    }

    /**
     * Verificar conquistas de participação
     */
    private function checkParticipationAchievements($user): void
    {
        // Conquista: Participação em simulados
        $totalParticipacoes = $user->simuladoTentativas()
            ->where('status', 'completed')
            ->count();
            
        if ($totalParticipacoes === 1) {
            $this->awardAchievement($user, 'primeira_participacao', 'Primeira Participação');
        } elseif ($totalParticipacoes === 5) {
            $this->awardAchievement($user, 'participacao_ativa', 'Participação Ativa');
        } elseif ($totalParticipacoes === 10) {
            $this->awardAchievement($user, 'participacao_dedicada', 'Participação Dedicada');
        }
    }

    /**
     * Conceder conquista ao usuário
     */
    private function awardAchievement($user, $codigo, $titulo): void
    {
        // Verificar se já tem a conquista
        $existingAchievement = UserAchievement::where('user_id', $user->id)
            ->whereHas('achievement', function($query) use ($codigo) {
                $query->where('codigo', $codigo);
            })
            ->first();
            
        if ($existingAchievement) {
            return; // Já tem a conquista
        }
        
        // Buscar ou criar a conquista
        $achievement = Achievement::firstOrCreate(
            ['codigo' => $codigo],
            [
                'titulo' => $titulo,
                'descricao' => "Conquista desbloqueada: {$titulo}",
                'pontos_recompensa' => 50,
                'icone' => '🏆',
                'categoria' => 'simulados'
            ]
        );
        
        // Conceder conquista
        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'concedido_em' => now()
        ]);
        
        // Adicionar pontos da conquista
        $this->gamificationService->addPoints($user, $achievement->pontos_recompensa, 'conquista_desbloqueada', [
            'achievement_id' => $achievement->id,
            'achievement_codigo' => $codigo
        ]);
        
        // Enviar notificação
        $this->notificationService->sendNotification($user, [
            'title' => "🏆 Nova Conquista: {$achievement->titulo}",
            'message' => "Parabéns! Você desbloqueou a conquista '{$achievement->titulo}' e ganhou {$achievement->pontos_recompensa} pontos!",
            'type' => 'achievement',
            'data' => [
                'achievement_id' => $achievement->id,
                'pontos_ganhos' => $achievement->pontos_recompensa
            ]
        ]);
    }

    /**
     * Atualizar estatísticas do usuário
     */
    private function updateUserStats($user): void
    {
        // Atualizar gamificação
        $userGamification = $user->gamification;
        if ($userGamification) {
            $userGamification->update([
                'simulados_completados' => $user->simuladoTentativas()
                    ->where('status', 'completed')
                    ->count(),
                'simulados_aprovados' => $user->simuladoTentativas()
                    ->where('status', 'completed')
                    ->where('score', '>=', '70')
                    ->count(),
                'ultima_atividade' => now()
            ]);
        }
    }
} 