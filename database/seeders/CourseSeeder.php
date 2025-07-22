<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use App\Models\CourseEnrollment;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Criando cursos de exemplo...');

        // 1. Curso de Onboarding (Obrigatório)
        $onboardingCourse = Course::create([
            'title' => 'Onboarding da Empresa HCP',
            'description' => 'Bem-vindo à HCP! Este curso apresenta nossa história, cultura, valores, políticas essenciais e ferramentas que você utilizará no dia a dia. É o primeiro passo para se integrar completamente à nossa equipe e compreender como contribuir para o sucesso da empresa.',
            'short_description' => 'Curso introdutório essencial para novos colaboradores da HCP',
            'duration_hours' => 8,
            'difficulty_level' => 'beginner',
            'type' => 'mandatory',
            'is_active' => true,
            'order_index' => 1,
            'tags' => ['onboarding', 'cultura', 'políticas', 'introdução', 'obrigatório']
        ]);

        // 2. Curso de Segurança da Informação (Obrigatório)
        $securityCourse = Course::create([
            'title' => 'Segurança da Informação',
            'description' => 'Aprenda sobre as melhores práticas de segurança da informação, proteção de dados, políticas de senha, phishing, e como manter a segurança digital da empresa. Este curso é essencial para todos os colaboradores.',
            'short_description' => 'Fundamentos de segurança digital e proteção de dados',
            'duration_hours' => 4,
            'difficulty_level' => 'beginner',
            'type' => 'mandatory',
            'is_active' => true,
            'order_index' => 2,
            'tags' => ['segurança', 'informação', 'dados', 'proteção', 'obrigatório']
        ]);

        // 3. Curso de Gestão de Projetos (Opcional)
        $projectManagementCourse = Course::create([
            'title' => 'Gestão de Projetos Ágeis',
            'description' => 'Domine as metodologias ágeis de gestão de projetos, incluindo Scrum, Kanban e ferramentas de produtividade. Ideal para líderes de equipe e profissionais que desejam aprimorar suas habilidades de gestão.',
            'short_description' => 'Metodologias ágeis e ferramentas de gestão de projetos',
            'duration_hours' => 12,
            'difficulty_level' => 'intermediate',
            'type' => 'optional',
            'is_active' => true,
            'order_index' => 3,
            'tags' => ['gestão', 'projetos', 'scrum', 'kanban', 'liderança']
        ]);

        $this->command->info('✅ Cursos criados com sucesso!');
        $this->command->info('📚 Criando módulos para os cursos...');

        // Módulos para o Curso de Onboarding
        $onboardingModules = [
            [
                'title' => 'História da HCP',
                'description' => 'Conheça a trajetória da HCP, desde sua fundação até os dias atuais, principais marcos e conquistas.',
                'duration_minutes' => 45,
                'content_type' => 'video',
                'order_index' => 1,
                'category' => 'culture'
            ],
            [
                'title' => 'Cultura e Valores',
                'description' => 'Entenda nossa cultura organizacional, valores fundamentais e como eles guiam nossas decisões diárias.',
                'duration_minutes' => 60,
                'content_type' => 'interactive',
                'order_index' => 2,
                'category' => 'culture'
            ],
            [
                'title' => 'Estrutura Organizacional',
                'description' => 'Conheça a estrutura da empresa, departamentos, hierarquia e principais contatos.',
                'duration_minutes' => 30,
                'content_type' => 'text',
                'order_index' => 3,
                'category' => 'hr'
            ],
            [
                'title' => 'Políticas e Procedimentos',
                'description' => 'Políticas de RH, código de conduta, procedimentos internos e regulamentações importantes.',
                'duration_minutes' => 90,
                'content_type' => 'document',
                'order_index' => 4,
                'category' => 'compliance'
            ],
            [
                'title' => 'Ferramentas e Sistemas',
                'description' => 'Apresentação das principais ferramentas e sistemas utilizados no dia a dia de trabalho.',
                'duration_minutes' => 75,
                'content_type' => 'video',
                'order_index' => 5,
                'category' => 'it'
            ]
        ];

        foreach ($onboardingModules as $moduleData) {
            Module::create(array_merge($moduleData, [
                'course_id' => $onboardingCourse->id,
                'is_active' => true,
                'estimated_duration' => $moduleData['duration_minutes']
            ]));
        }

        // Módulos para o Curso de Segurança da Informação
        $securityModules = [
            [
                'title' => 'Fundamentos de Segurança',
                'description' => 'Conceitos básicos de segurança da informação e principais ameaças digitais.',
                'duration_minutes' => 45,
                'content_type' => 'video',
                'order_index' => 1,
                'category' => 'security'
            ],
            [
                'title' => 'Políticas de Senha',
                'description' => 'Como criar senhas seguras, uso de gerenciadores de senha e autenticação de dois fatores.',
                'duration_minutes' => 30,
                'content_type' => 'interactive',
                'order_index' => 2,
                'category' => 'security'
            ],
            [
                'title' => 'Phishing e Engenharia Social',
                'description' => 'Identificação e prevenção de ataques de phishing e técnicas de engenharia social.',
                'duration_minutes' => 40,
                'content_type' => 'video',
                'order_index' => 3,
                'category' => 'security'
            ],
            [
                'title' => 'Proteção de Dados',
                'description' => 'LGPD, proteção de dados pessoais e boas práticas de manuseio de informações.',
                'duration_minutes' => 50,
                'content_type' => 'document',
                'order_index' => 4,
                'category' => 'compliance'
            ]
        ];

        foreach ($securityModules as $moduleData) {
            Module::create(array_merge($moduleData, [
                'course_id' => $securityCourse->id,
                'is_active' => true,
                'estimated_duration' => $moduleData['duration_minutes']
            ]));
        }

        // Módulos para o Curso de Gestão de Projetos
        $projectModules = [
            [
                'title' => 'Introdução às Metodologias Ágeis',
                'description' => 'História e princípios das metodologias ágeis, diferenças com métodos tradicionais.',
                'duration_minutes' => 60,
                'content_type' => 'video',
                'order_index' => 1,
                'category' => 'processes'
            ],
            [
                'title' => 'Framework Scrum',
                'description' => 'Papéis, eventos e artefatos do Scrum. Como implementar Scrum em sua equipe.',
                'duration_minutes' => 90,
                'content_type' => 'interactive',
                'order_index' => 2,
                'category' => 'processes'
            ],
            [
                'title' => 'Método Kanban',
                'description' => 'Princípios do Kanban, visualização do fluxo de trabalho e melhoria contínua.',
                'duration_minutes' => 75,
                'content_type' => 'video',
                'order_index' => 3,
                'category' => 'processes'
            ],
            [
                'title' => 'Ferramentas de Gestão',
                'description' => 'Jira, Trello, Azure DevOps e outras ferramentas para gestão ágil de projetos.',
                'duration_minutes' => 80,
                'content_type' => 'interactive',
                'order_index' => 4,
                'category' => 'it'
            ]
        ];

        foreach ($projectModules as $moduleData) {
            Module::create(array_merge($moduleData, [
                'course_id' => $projectManagementCourse->id,
                'is_active' => true,
                'estimated_duration' => $moduleData['duration_minutes']
            ]));
        }

        $this->command->info('✅ Módulos criados com sucesso!');
        $this->command->info('👥 Criando inscrições de exemplo...');

        // Criar algumas inscrições de exemplo
        $users = User::where('role', '!=', 'admin')->take(10)->get();
        
        foreach ($users as $user) {
            // Inscrever todos os usuários nos cursos obrigatórios
            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $onboardingCourse->id,
                'enrolled_at' => now()->subDays(rand(1, 30)),
                'status' => ['enrolled', 'in_progress', 'completed'][rand(0, 2)],
                'progress_percentage' => rand(0, 100)
            ]);

            CourseEnrollment::create([
                'user_id' => $user->id,
                'course_id' => $securityCourse->id,
                'enrolled_at' => now()->subDays(rand(1, 20)),
                'status' => ['enrolled', 'in_progress', 'completed'][rand(0, 2)],
                'progress_percentage' => rand(0, 100)
            ]);

            // Alguns usuários se inscrevem em cursos opcionais
            if (rand(0, 1)) {
                CourseEnrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $projectManagementCourse->id,
                    'enrolled_at' => now()->subDays(rand(1, 15)),
                    'status' => ['enrolled', 'in_progress'][rand(0, 1)],
                    'progress_percentage' => rand(0, 80)
                ]);
            }
        }

        $this->command->info('✅ Inscrições criadas com sucesso!');
        $this->command->info('');
        $this->command->info('🎉 Sistema de Cursos configurado com sucesso!');
        $this->command->info('');
        $this->command->info('📋 Resumo:');
        $this->command->info("   • {$onboardingCourse->modules()->count()} módulos no curso de Onboarding");
        $this->command->info("   • {$securityCourse->modules()->count()} módulos no curso de Segurança");
        $this->command->info("   • {$projectManagementCourse->modules()->count()} módulos no curso de Gestão de Projetos");
        $this->command->info("   • " . CourseEnrollment::count() . " inscrições de exemplo criadas");
        $this->command->info('');
        $this->command->info('🚀 Acesse /courses para ver os cursos disponíveis!');
    }
}