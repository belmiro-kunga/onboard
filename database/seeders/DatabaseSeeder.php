<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Executa os seeders do banco de dados.
     */
    public function run(): void
    {
        // Chame outros seeders aqui
        // $this->call(UserSeeder::class);
        
        $this->call([
            AdminUserSeeder::class,
            TestUserSeeder::class,
            CourseSeeder::class,
            ModuleSeeder::class,
            QuizSeeder::class,
            AchievementSeeder::class,
            SimuladoSeeder::class,
            DashboardDataSeeder::class,
        ]);
    }
}
