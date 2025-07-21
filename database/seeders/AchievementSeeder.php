<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'Primeiro Passo',
                'description' => 'Complete seu primeiro módulo de onboarding',
                'icon' => 'first-step.png',
                'category' => 'learning',
                'type' => 'first_module',
                'condition_data' => [],
                'points_reward' => 50,
                'is_active' => true,
                'rarity' => 'common',
                'unlock_message' => 'Parabéns! Você deu o primeiro passo no seu onboarding!',
            ],
            [
                'name' => 'Estudante Dedicado',
                'description' => 'Complete 5 módulos',
                'icon' => 'dedicated-student.png',
                'category' => 'learning',
                'type' => 'modules_completed',
                'condition_data' => ['modules_count' => 5],
                'points_reward' => 100,
                'is_active' => true,
                'rarity' => 'common',
                'unlock_message' => 'Excelente! Você está se tornando um especialista!',
            ],
            [
                'name' => 'Mestre do Conhecimento',
                'description' => 'Complete 10 módulos',
                'icon' => 'knowledge-master.png',
                'category' => 'learning',
                'type' => 'modules_completed',
                'condition_data' => ['modules_count' => 10],
                'points_reward' => 200,
                'is_active' => true,
                'rarity' => 'rare',
                'unlock_message' => 'Incrível! Você é um verdadeiro mestre do conhecimento!',
            ],
            [
                'name' => 'Pontuação Perfeita',
                'description' => 'Obtenha 100% em um quiz',
                'icon' => 'perfect-score.png',
                'category' => 'performance',
                'type' => 'perfect_score',
                'condition_data' => ['perfect_count' => 1],
                'points_reward' => 75,
                'is_active' => true,
                'rarity' => 'rare',
                'unlock_message' => 'Perfeito! Você demonstrou domínio total do conteúdo!',
            ],
            [
                'name' => 'Sequência de Sucesso',
                'description' => 'Aprove em 3 quizzes consecutivos',
                'icon' => 'success-streak.png',
                'category' => 'performance',
                'type' => 'quiz_streak',
                'condition_data' => ['streak_count' => 3],
                'points_reward' => 150,
                'is_active' => true,
                'rarity' => 'epic',
                'unlock_message' => 'Fantástico! Você está em uma sequência incrível!',
            ],
            [
                'name' => 'Colecionador de Pontos',
                'description' => 'Acumule 500 pontos',
                'icon' => 'point-collector.png',
                'category' => 'engagement',
                'type' => 'points_earned',
                'condition_data' => ['points_required' => 500],
                'points_reward' => 100,
                'is_active' => true,
                'rarity' => 'common',
                'unlock_message' => 'Parabéns! Você está acumulando conhecimento!',
            ],
            [
                'name' => 'Frequente',
                'description' => 'Faça login por 7 dias consecutivos',
                'icon' => 'frequent-user.png',
                'category' => 'engagement',
                'type' => 'login_streak',
                'condition_data' => ['days_required' => 7],
                'points_reward' => 125,
                'is_active' => true,
                'rarity' => 'rare',
                'unlock_message' => 'Consistência é a chave do sucesso!',
            ],
            [
                'name' => 'Velocista',
                'description' => 'Complete um módulo em menos de 30 minutos',
                'icon' => 'speed-demon.png',
                'category' => 'performance',
                'type' => 'speed_demon',
                'condition_data' => ['max_time_minutes' => 30],
                'points_reward' => 80,
                'is_active' => true,
                'rarity' => 'epic',
                'unlock_message' => 'Rápido e eficiente! Você é um velocista do conhecimento!',
            ],
        ];

        foreach ($achievements as $achievementData) {
            Achievement::create($achievementData);
        }

        $this->command->info('🏆 Achievements de teste criados com sucesso!');
    }
} 