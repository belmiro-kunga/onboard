<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserGamification;
use App\Models\UserProgress;
use App\Models\Notification;
use App\Models\PointsHistory;
use App\Models\Achievement;
use App\Models\Module;
use Carbon\Carbon;

class DashboardDataSeeder extends Seeder
{
    /**
     * Executa o seeder de dados do dashboard.
     */
    public function run(): void
    {
        $this->command->info('🚀 Populando dados do dashboard...');
        
        // Buscar usuários existentes
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('⚠️  Nenhum usuário encontrado. Execute primeiro o TestUserSeeder.');
            return;
        }

        // Popular dados para cada usuário
        foreach ($users as $user) {
            $this->createUserGamificationData($user);
            $this->createUserProgressData($user);
            $this->createNotificationsData($user);
            $this->createPointsHistoryData($user);
        }

        // $this->createAchievementsData(); // Achievements já existem
        $this->assignRandomAchievements();
        
        $this->command->info('✅ Dados do dashboard populados com sucesso!');
    }

    /**
     * Criar dados de gamificação para o usuário
     */
    private function createUserGamificationData(User $user): void
    {
        // Verificar se já existe
        if ($user->gamification) {
            return;
        }

        $totalPoints = rand(100, 5000);
        $level = $this->calculateLevel($totalPoints);
        
        UserGamification::create([
            'user_id' => $user->id,
            'total_points' => $totalPoints,
            'current_level' => $level,
            'rank_position' => rand(1, 50),
            'achievements_count' => rand(0, 15),
            'streak_days' => rand(0, 30),
            'longest_streak' => rand(5, 45),
            'last_activity_date' => Carbon::now()->subDays(rand(0, 3)),
            'level_progress' => rand(10, 90),
            'badges' => [
                'first_module' => true,
                'speed_demon' => rand(0, 1) === 1,
                'perfect_score' => rand(0, 1) === 1,
                'knowledge_seeker' => rand(0, 1) === 1,
            ],
            'statistics' => [
                'modules_completed' => rand(0, 16),
                'quizzes_passed' => rand(0, 20),
                'perfect_scores' => rand(0, 5),
                'time_spent_minutes' => rand(60, 1200),
                'average_score' => rand(70, 100),
            ]
        ]);
    }

    /**
     * Criar dados de progresso para o usuário
     */
    private function createUserProgressData(User $user): void
    {
        $modules = Module::where('is_active', true)->get();
        
        foreach ($modules as $module) {
            // Chance de 70% de ter progresso no módulo
            if (rand(1, 10) <= 7) {
                $progress = rand(0, 100);
                $status = $this->determineStatus($progress);
                
                UserProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'module_id' => $module->id,
                    ],
                    [
                        'progress_percentage' => $progress,
                        'status' => $status,
                        'started_at' => Carbon::now()->subDays(rand(1, 30)),
                        'completed_at' => $status === 'completed' ? Carbon::now()->subDays(rand(0, 15)) : null,
                        'time_spent' => rand(10, $module->estimated_duration + 20),
                        'last_accessed_at' => Carbon::now()->subDays(rand(0, 7)),
                    ]
                );
            }
        }
    }

    /**
     * Criar notificações para o usuário
     */
    private function createNotificationsData(User $user): void
    {
        $notificationTypes = [
            [
                'title' => 'Nova conquista desbloqueada! 🎉',
                'message' => 'Parabéns! Você conquistou o badge "Primeiro Passo" ao completar seu primeiro módulo.',
                'type' => 'achievement_earned',
                'icon' => 'star',
                'color' => 'yellow'
            ],
            [
                'title' => 'Módulo concluído com sucesso! ✅',
                'message' => 'Você completou o módulo "Segurança da Informação" com 95% de aproveitamento.',
                'type' => 'module_completed',
                'icon' => 'check-circle',
                'color' => 'green'
            ],
            [
                'title' => 'Novo módulo disponível! 📚',
                'message' => 'O módulo "Cultura Organizacional" está agora disponível para você.',
                'type' => 'module_available',
                'icon' => 'book',
                'color' => 'blue'
            ],
            [
                'title' => 'Sequência de 7 dias! 🔥',
                'message' => 'Incrível! Você manteve uma sequência de aprendizado por 7 dias consecutivos.',
                'type' => 'streak_milestone',
                'icon' => 'fire',
                'color' => 'orange'
            ],
            [
                'title' => 'Pontuação perfeita! 💯',
                'message' => 'Excelente trabalho! Você obteve 100% no quiz de "Políticas de RH".',
                'type' => 'perfect_score',
                'icon' => 'target',
                'color' => 'purple'
            ],
            [
                'title' => 'Subiu de nível! ⬆️',
                'message' => 'Parabéns! Você avançou para o nível "Intermediário" com seus 1000 pontos.',
                'type' => 'level_up',
                'icon' => 'trending-up',
                'color' => 'indigo'
            ]
        ];

        // Criar 3-8 notificações por usuário
        $notificationCount = rand(3, 8);
        
        for ($i = 0; $i < $notificationCount; $i++) {
            $notification = $notificationTypes[array_rand($notificationTypes)];
            
            Notification::create([
                'user_id' => $user->id,
                'title' => $notification['title'],
                'message' => $notification['message'],
                'type' => $notification['type'],
                'icon' => $notification['icon'],
                'color' => $notification['color'],
                'data' => [
                    'action_url' => '/dashboard',
                    'priority' => rand(1, 3),
                ],
                'read_at' => rand(0, 1) === 1 ? Carbon::now()->subHours(rand(1, 48)) : null,
                'created_at' => Carbon::now()->subHours(rand(1, 168)), // Última semana
            ]);
        }
    }

    /**
     * Criar histórico de pontos para o usuário
     */
    private function createPointsHistoryData(User $user): void
    {
        $reasons = [
            'Módulo completado: Segurança da Informação',
            'Quiz aprovado: Políticas de RH',
            'Primeira conquista desbloqueada',
            'Sequência de 7 dias mantida',
            'Pontuação perfeita no quiz',
            'Módulo completado: Cultura Organizacional',
            'Participação ativa na plataforma',
            'Quiz aprovado: Sistemas Corporativos',
            'Conquista: Speed Demon',
            'Módulo completado: LGPD e Proteção de Dados',
        ];

        // Criar 10-25 entradas de histórico
        $historyCount = rand(10, 25);
        
        for ($i = 0; $i < $historyCount; $i++) {
            $points = rand(10, 200);
            $reason = $reasons[array_rand($reasons)];
            
            PointsHistory::create([
                'user_id' => $user->id,
                'points' => $points,
                'reason' => $reason,
                'reference_type' => rand(0, 1) === 1 ? 'App\\Models\\Module' : 'App\\Models\\Quiz',
                'reference_id' => rand(1, 10),
                'multiplier' => rand(0, 1) === 1 ? 1.5 : 1.0,
                'bonus_points' => rand(0, 1) === 1 ? rand(5, 50) : 0,
                'created_at' => Carbon::now()->subDays(rand(0, 60)),
            ]);
        }
    }

    /**
     * Criar conquistas (achievements)
     */
    private function createAchievementsData(): void
    {
        $achievements = [
            [
                'name' => 'Primeiro Passo',
                'description' => 'Complete seu primeiro módulo de treinamento',
                'icon' => 'play-circle',
                'category' => 'general',
                'type' => 'first_module',
                'condition_data' => ['modules_count' => 1],
                'points_reward' => 50,
                'rarity' => 'common',
                'unlock_message' => 'Parabéns! Você deu o primeiro passo na sua jornada de aprendizado!'
            ],
            [
                'name' => 'Dedicado',
                'description' => 'Complete 5 módulos de treinamento',
                'icon' => 'book-open',
                'category' => 'general',
                'type' => 'modules_completed',
                'condition_data' => ['modules_count' => 5],
                'points_reward' => 150,
                'rarity' => 'uncommon',
                'unlock_message' => 'Sua dedicação está sendo recompensada!'
            ],
            [
                'name' => 'Especialista',
                'description' => 'Complete 10 módulos de treinamento',
                'icon' => 'award',
                'category' => 'general',
                'type' => 'modules_completed',
                'condition_data' => ['modules_count' => 10],
                'points_reward' => 300,
                'rarity' => 'rare',
                'unlock_message' => 'Você está se tornando um verdadeiro especialista!'
            ],
            [
                'name' => 'Mestre do Conhecimento',
                'description' => 'Complete todos os módulos disponíveis',
                'icon' => 'crown',
                'category' => 'general',
                'type' => 'modules_completed',
                'condition_data' => ['modules_count' => 16],
                'points_reward' => 500,
                'rarity' => 'legendary',
                'unlock_message' => 'Incrível! Você dominou todo o conteúdo disponível!'
            ],
            [
                'name' => 'Colecionador de Pontos',
                'description' => 'Acumule 1000 pontos',
                'icon' => 'star',
                'category' => 'general',
                'type' => 'points_earned',
                'condition_data' => ['points_required' => 1000],
                'points_reward' => 100,
                'rarity' => 'uncommon',
                'unlock_message' => 'Seus esforços estão sendo bem recompensados!'
            ],
            [
                'name' => 'Milionário em Pontos',
                'description' => 'Acumule 5000 pontos',
                'icon' => 'gem',
                'category' => 'general',
                'type' => 'points_earned',
                'condition_data' => ['points_required' => 5000],
                'points_reward' => 250,
                'rarity' => 'epic',
                'unlock_message' => 'Você é oficialmente um milionário em pontos!'
            ],
            [
                'name' => 'Perfeccionista',
                'description' => 'Obtenha pontuação perfeita em 3 quizzes',
                'icon' => 'target',
                'category' => 'general',
                'type' => 'perfect_score',
                'condition_data' => ['perfect_count' => 3],
                'points_reward' => 200,
                'rarity' => 'rare',
                'unlock_message' => 'Perfeição é o seu padrão!'
            ],
            [
                'name' => 'Sequência de Fogo',
                'description' => 'Mantenha uma sequência de 7 dias',
                'icon' => 'fire',
                'category' => 'general',
                'type' => 'login_streak',
                'condition_data' => ['days_required' => 7],
                'points_reward' => 150,
                'rarity' => 'uncommon',
                'unlock_message' => 'Sua consistência é impressionante!'
            ],
            [
                'name' => 'Maratonista',
                'description' => 'Mantenha uma sequência de 30 dias',
                'icon' => 'zap',
                'category' => 'general',
                'type' => 'login_streak',
                'condition_data' => ['days_required' => 30],
                'points_reward' => 400,
                'rarity' => 'epic',
                'unlock_message' => 'Você é um verdadeiro maratonista do aprendizado!'
            ],
            [
                'name' => 'Speed Demon',
                'description' => 'Complete um módulo em menos de 20 minutos',
                'icon' => 'clock',
                'category' => 'general',
                'type' => 'speed_demon',
                'condition_data' => ['max_time_minutes' => 20],
                'points_reward' => 100,
                'rarity' => 'rare',
                'unlock_message' => 'Velocidade e eficiência são suas marcas!'
            ],
            [
                'name' => 'Explorador',
                'description' => 'Complete módulos de 3 categorias diferentes',
                'icon' => 'compass',
                'category' => 'general',
                'type' => 'knowledge_seeker',
                'condition_data' => ['categories' => ['culture', 'hr', 'security']],
                'points_reward' => 200,
                'rarity' => 'uncommon',
                'unlock_message' => 'Sua curiosidade não tem limites!'
            ],
            [
                'name' => 'Guru da Segurança',
                'description' => 'Complete todos os módulos de segurança',
                'icon' => 'shield',
                'category' => 'general',
                'type' => 'knowledge_seeker',
                'condition_data' => ['categories' => ['security']],
                'points_reward' => 250,
                'rarity' => 'rare',
                'unlock_message' => 'Você é agora um especialista em segurança!'
            ]
        ];

        foreach ($achievements as $achievementData) {
            Achievement::updateOrCreate(
                ['name' => $achievementData['name']],
                $achievementData
            );
        }
    }

    /**
     * Atribuir conquistas aleatórias aos usuários
     */
    private function assignRandomAchievements(): void
    {
        $users = User::all();
        $achievements = Achievement::where('is_active', true)->get();

        foreach ($users as $user) {
            // Cada usuário ganha 2-6 conquistas aleatórias
            $achievementCount = rand(2, 6);
            $randomAchievements = $achievements->random($achievementCount);

            foreach ($randomAchievements as $achievement) {
                // Verificar se já não possui
                if (!$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                    $user->achievements()->attach($achievement->id, [
                        'earned_at' => Carbon::now()->subDays(rand(0, 30)),
                        'points_awarded' => $achievement->points_reward,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Calcular nível baseado nos pontos
     */
    private function calculateLevel(int $points): string
    {
        return match(true) {
            $points >= 5000 => 'Master',
            $points >= 2500 => 'Expert',
            $points >= 1000 => 'Advanced',
            $points >= 500 => 'Intermediate',
            $points >= 100 => 'Explorer',
            $points >= 50 => 'Beginner',
            default => 'Rookie'
        };
    }

    /**
     * Determinar status baseado no progresso
     */
    private function determineStatus(int $progress): string
    {
        return match(true) {
            $progress >= 100 => 'completed',
            $progress > 0 => 'in_progress',
            default => 'not_started'
        };
    }
}