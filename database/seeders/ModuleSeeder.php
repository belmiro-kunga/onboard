<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Executa o seeder de módulos.
     */
    public function run(): void
    {
        // Limpar módulos existentes (respeitando foreign keys)
        Module::query()->delete();

        $modules = [
            // === CATEGORIA: CULTURA ORGANIZACIONAL ===
            [
                'title' => 'Bem-vindo à Hemera Capital Partners',
                'description' => 'Conheça nossa história, missão, visão e valores. Descubra como você faz parte da nossa jornada de excelência no mercado financeiro.',
                'category' => 'culture',
                'order_index' => 1,
                'is_active' => true,
                'points_reward' => 100,
                'estimated_duration' => 25,
                'content_type' => 'video',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'video_url' => 'https://example.com/videos/welcome-hcp.mp4',
                    'transcript' => 'Bem-vindos à Hemera Capital Partners, onde transformamos oportunidades em resultados excepcionais...',
                    'key_points' => [
                        'História da empresa fundada em 2010',
                        'Missão: Maximizar valor para nossos clientes',
                        'Visão: Ser referência em gestão de investimentos',
                        'Valores: Integridade, Excelência, Inovação'
                    ]
                ]
            ],
            [
                'title' => 'Nossa Cultura de Excelência',
                'description' => 'Entenda os pilares da nossa cultura organizacional e como cada colaborador contribui para o sucesso coletivo.',
                'category' => 'culture',
                'order_index' => 2,
                'is_active' => true,
                'points_reward' => 80,
                'estimated_duration' => 20,
                'content_type' => 'interactive',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'sections' => [
                        'Pilares Culturais',
                        'Código de Conduta',
                        'Diversidade e Inclusão',
                        'Responsabilidade Social'
                    ]
                ]
            ],
            [
                'title' => 'Estrutura Organizacional e Hierarquia',
                'description' => 'Compreenda nossa estrutura organizacional, departamentos e como as equipes se conectam para entregar resultados.',
                'category' => 'culture',
                'order_index' => 3,
                'is_active' => true,
                'points_reward' => 60,
                'estimated_duration' => 15,
                'content_type' => 'document',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'departments' => [
                        'Diretoria Executiva',
                        'Gestão de Investimentos',
                        'Análise de Risco',
                        'Relacionamento com Clientes',
                        'Operações e TI',
                        'Recursos Humanos',
                        'Compliance e Jurídico'
                    ]
                ]
            ],

            // === CATEGORIA: RECURSOS HUMANOS ===
            [
                'title' => 'Políticas de RH e Benefícios',
                'description' => 'Conheça todas as políticas de recursos humanos, benefícios oferecidos e seus direitos como colaborador.',
                'category' => 'hr',
                'order_index' => 1,
                'is_active' => true,
                'points_reward' => 120,
                'estimated_duration' => 35,
                'content_type' => 'document',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'topics' => [
                        'Jornada de Trabalho e Flexibilidade',
                        'Plano de Saúde e Odontológico',
                        'Vale Refeição e Alimentação',
                        'Programa de Participação nos Lucros',
                        'Licenças e Afastamentos',
                        'Desenvolvimento Profissional',
                        'Programa de Bem-estar'
                    ]
                ]
            ],
            [
                'title' => 'Avaliação de Performance e Carreira',
                'description' => 'Entenda como funciona nosso processo de avaliação de performance e as oportunidades de crescimento na carreira.',
                'category' => 'hr',
                'order_index' => 2,
                'is_active' => true,
                'points_reward' => 90,
                'estimated_duration' => 25,
                'content_type' => 'video',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'evaluation_cycles' => 'Semestral',
                    'career_paths' => [
                        'Analista → Sênior → Especialista → Coordenador',
                        'Coordenador → Gerente → Diretor',
                        'Trilha Técnica vs Trilha Gestão'
                    ]
                ]
            ],
            [
                'title' => 'Código de Ética e Conduta',
                'description' => 'Aprenda sobre nossos padrões éticos, código de conduta e como aplicá-los no dia a dia profissional.',
                'category' => 'hr',
                'order_index' => 3,
                'is_active' => true,
                'points_reward' => 100,
                'estimated_duration' => 30,
                'content_type' => 'interactive',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'scenarios' => [
                        'Conflito de Interesses',
                        'Informações Privilegiadas',
                        'Relacionamento com Fornecedores',
                        'Assédio e Discriminação',
                        'Uso de Recursos da Empresa'
                    ]
                ]
            ],

            // === CATEGORIA: SEGURANÇA DA INFORMAÇÃO ===
            [
                'title' => 'Fundamentos de Segurança da Informação',
                'description' => 'Aprenda os conceitos básicos de segurança da informação e sua importância no mercado financeiro.',
                'category' => 'security',
                'order_index' => 1,
                'is_active' => true,
                'points_reward' => 150,
                'estimated_duration' => 40,
                'content_type' => 'interactive',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'principles' => [
                        'Confidencialidade',
                        'Integridade',
                        'Disponibilidade',
                        'Autenticidade',
                        'Não-repúdio'
                    ]
                ]
            ],
            [
                'title' => 'Gestão de Senhas e Autenticação',
                'description' => 'Boas práticas para criação e gerenciamento de senhas seguras, autenticação multifator e proteção de contas.',
                'category' => 'security',
                'order_index' => 2,
                'is_active' => true,
                'points_reward' => 80,
                'estimated_duration' => 20,
                'content_type' => 'interactive',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'password_rules' => [
                        'Mínimo 12 caracteres',
                        'Combinação de letras, números e símbolos',
                        'Não usar informações pessoais',
                        'Senha única para cada sistema',
                        'Uso de gerenciador de senhas'
                    ]
                ]
            ],
            [
                'title' => 'Phishing e Engenharia Social',
                'description' => 'Identifique e proteja-se contra ataques de phishing, engenharia social e outras ameaças cibernéticas.',
                'category' => 'security',
                'order_index' => 3,
                'is_active' => true,
                'points_reward' => 120,
                'estimated_duration' => 30,
                'content_type' => 'interactive',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'attack_types' => [
                        'E-mail Phishing',
                        'SMS Phishing (Smishing)',
                        'Vishing (Voice Phishing)',
                        'Spear Phishing',
                        'Pretexting'
                    ]
                ]
            ],
            [
                'title' => 'LGPD e Proteção de Dados',
                'description' => 'Entenda a Lei Geral de Proteção de Dados e como aplicá-la no tratamento de informações de clientes.',
                'category' => 'security',
                'order_index' => 4,
                'is_active' => true,
                'points_reward' => 140,
                'estimated_duration' => 35,
                'content_type' => 'document',
                'difficulty_level' => 'advanced',
                'content_data' => [
                    'lgpd_principles' => [
                        'Finalidade',
                        'Adequação',
                        'Necessidade',
                        'Livre Acesso',
                        'Qualidade dos Dados',
                        'Transparência',
                        'Segurança',
                        'Prevenção',
                        'Não Discriminação',
                        'Responsabilização'
                    ]
                ]
            ],

            // === CATEGORIA: TECNOLOGIA DA INFORMAÇÃO ===
            [
                'title' => 'Sistemas Corporativos - Visão Geral',
                'description' => 'Conheça todos os sistemas utilizados na empresa e como acessá-los de forma segura e eficiente.',
                'category' => 'it',
                'order_index' => 1,
                'is_active' => true,
                'points_reward' => 90,
                'estimated_duration' => 25,
                'content_type' => 'interactive',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'systems' => [
                        'Sistema de Gestão de Investimentos',
                        'CRM - Relacionamento com Clientes',
                        'ERP - Gestão Empresarial',
                        'Sistema de RH',
                        'Plataforma de Comunicação',
                        'Sistema de Documentos'
                    ]
                ]
            ],
            [
                'title' => 'Microsoft 365 e Ferramentas de Produtividade',
                'description' => 'Domine o uso do Microsoft 365, Teams, SharePoint e outras ferramentas de produtividade da empresa.',
                'category' => 'it',
                'order_index' => 2,
                'is_active' => true,
                'points_reward' => 70,
                'estimated_duration' => 30,
                'content_type' => 'video',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'tools' => [
                        'Outlook - E-mail e Calendário',
                        'Teams - Comunicação e Reuniões',
                        'SharePoint - Documentos Compartilhados',
                        'OneDrive - Armazenamento Pessoal',
                        'Power BI - Relatórios e Dashboards'
                    ]
                ]
            ],
            [
                'title' => 'Backup e Recuperação de Dados',
                'description' => 'Aprenda sobre nossas políticas de backup, como recuperar arquivos e proteger seus dados de trabalho.',
                'category' => 'it',
                'order_index' => 3,
                'is_active' => true,
                'points_reward' => 60,
                'estimated_duration' => 20,
                'content_type' => 'document',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'backup_types' => [
                        'Backup Automático Diário',
                        'Backup Semanal Completo',
                        'Versionamento de Arquivos',
                        'Recuperação de E-mails',
                        'Restore de Sistemas'
                    ]
                ]
            ],

            // === CATEGORIA: PROCESSOS E PROCEDIMENTOS ===
            [
                'title' => 'Fluxos de Trabalho e Aprovações',
                'description' => 'Entenda os principais fluxos de trabalho da empresa e como solicitar aprovações de forma eficiente.',
                'category' => 'processes',
                'order_index' => 1,
                'is_active' => true,
                'points_reward' => 80,
                'estimated_duration' => 25,
                'content_type' => 'interactive',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'workflows' => [
                        'Solicitação de Férias',
                        'Reembolso de Despesas',
                        'Compras e Fornecedores',
                        'Aprovação de Investimentos',
                        'Abertura de Contas de Clientes'
                    ]
                ]
            ],
            [
                'title' => 'Gestão de Documentos e Arquivos',
                'description' => 'Aprenda como organizar, nomear e armazenar documentos seguindo nossos padrões corporativos.',
                'category' => 'processes',
                'order_index' => 2,
                'is_active' => true,
                'points_reward' => 60,
                'estimated_duration' => 20,
                'content_type' => 'document',
                'difficulty_level' => 'basic',
                'content_data' => [
                    'standards' => [
                        'Nomenclatura de Arquivos',
                        'Estrutura de Pastas',
                        'Controle de Versões',
                        'Retenção de Documentos',
                        'Classificação de Confidencialidade'
                    ]
                ]
            ],
            [
                'title' => 'Comunicação Interna e Externa',
                'description' => 'Diretrizes para comunicação eficaz, tanto interna quanto com clientes e parceiros externos.',
                'category' => 'processes',
                'order_index' => 3,
                'is_active' => true,
                'points_reward' => 70,
                'estimated_duration' => 22,
                'content_type' => 'interactive',
                'difficulty_level' => 'intermediate',
                'content_data' => [
                    'communication_channels' => [
                        'E-mail Corporativo',
                        'Microsoft Teams',
                        'Intranet Corporativa',
                        'Comunicados Oficiais',
                        'Redes Sociais Corporativas'
                    ]
                ]
            ]
        ];

        foreach ($modules as $moduleData) {
            Module::create($moduleData);
        }

        $this->command->info('🎯 ' . count($modules) . ' módulos completos criados com sucesso!');
        $this->command->info('📊 Categorias: Cultura, RH, Segurança, TI, Processos');
        $this->command->info('⏱️  Tempo total estimado: ' . array_sum(array_column($modules, 'estimated_duration')) . ' minutos');
        $this->command->info('🏆 Total de pontos disponíveis: ' . array_sum(array_column($modules, 'points_reward')) . ' pontos');
    }
}