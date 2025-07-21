<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\ModuleContent;

class ModuleContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpar conteúdos existentes
        ModuleContent::query()->delete();

        // Buscar módulos criados
        $modules = Module::all();

        foreach ($modules as $module) {
            $this->createContentForModule($module);
        }

        $this->command->info('📝 Conteúdos detalhados criados para todos os módulos!');
    }

    private function createContentForModule(Module $module)
    {
        switch ($module->title) {
            case 'Bem-vindo à Hemera Capital Partners':
                $this->createWelcomeContent($module);
                break;
            case 'Nossa Cultura de Excelência':
                $this->createCultureContent($module);
                break;
            case 'Políticas de RH e Benefícios':
                $this->createHRContent($module);
                break;
            case 'Fundamentos de Segurança da Informação':
                $this->createSecurityContent($module);
                break;
            case 'Sistemas Corporativos - Visão Geral':
                $this->createITContent($module);
                break;
            case 'Fluxos de Trabalho e Aprovações':
                $this->createProcessContent($module);
                break;
            default:
                $this->createGenericContent($module);
                break;
        }
    }

    private function createWelcomeContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Vídeo de Boas-vindas do CEO',
                'content_type' => 'video',
                'content_data' => [
                    'video_url' => 'https://example.com/videos/ceo-welcome.mp4',
                    'duration' => 8,
                    'transcript' => 'Olá e bem-vindos à Hemera Capital Partners! Sou [Nome do CEO] e estou muito feliz em recebê-los em nossa equipe...'
                ],
                'order_index' => 1,
                'duration' => 480, // 8 minutos em segundos
                'notes_enabled' => true,
                'bookmarks_enabled' => true
            ],
            [
                'title' => 'História da Empresa',
                'content_type' => 'text',
                'content_data' => [
                    'content' => 'A Hemera Capital Partners foi fundada em 2010 com a missão de democratizar o acesso a investimentos de qualidade. Desde então, crescemos consistentemente, sempre mantendo nossos valores de integridade, excelência e inovação.',
                    'key_facts' => [
                        'Fundada em 2010',
                        'Mais de R$ 5 bilhões sob gestão',
                        '500+ clientes ativos',
                        '50+ colaboradores especializados'
                    ]
                ],
                'order_index' => 2,
                'duration' => 300, // 5 minutos
                'notes_enabled' => true
            ],
            [
                'title' => 'Missão, Visão e Valores',
                'content_type' => 'interactive',
                'content_data' => [
                    'mission' => 'Maximizar o valor dos investimentos de nossos clientes através de estratégias inovadoras e gestão de risco eficiente.',
                    'vision' => 'Ser reconhecida como a principal gestora de investimentos do Brasil, referência em excelência e inovação.',
                    'values' => [
                        'Integridade: Agimos com transparência e ética em todas as nossas relações',
                        'Excelência: Buscamos sempre a melhor performance e qualidade em nossos serviços',
                        'Inovação: Utilizamos tecnologia e metodologias avançadas para criar soluções únicas',
                        'Responsabilidade: Assumimos compromisso com o sucesso de nossos clientes e colaboradores'
                    ]
                ],
                'order_index' => 3,
                'duration' => 720, // 12 minutos
                'interactive_markers' => [
                    ['time' => 120, 'title' => 'Nossa Missão', 'description' => 'Entenda nossa razão de existir'],
                    ['time' => 360, 'title' => 'Nossa Visão', 'description' => 'Para onde estamos caminhando'],
                    ['time' => 600, 'title' => 'Nossos Valores', 'description' => 'O que nos guia diariamente']
                ]
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }

    private function createCultureContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Pilares da Nossa Cultura',
                'content_type' => 'interactive',
                'content_data' => [
                    'pillars' => [
                        'Colaboração' => 'Trabalhamos juntos para alcançar objetivos comuns',
                        'Meritocracia' => 'Reconhecemos e recompensamos o desempenho excepcional',
                        'Diversidade' => 'Valorizamos diferentes perspectivas e experiências',
                        'Sustentabilidade' => 'Consideramos o impacto de longo prazo de nossas decisões'
                    ]
                ],
                'order_index' => 1,
                'duration' => 600, // 10 minutos
                'interactive_markers' => [
                    ['time' => 150, 'title' => 'Colaboração', 'description' => 'Como trabalhamos em equipe'],
                    ['time' => 300, 'title' => 'Meritocracia', 'description' => 'Reconhecimento por mérito'],
                    ['time' => 450, 'title' => 'Diversidade', 'description' => 'Valorizando diferenças']
                ]
            ],
            [
                'title' => 'Código de Conduta Resumido',
                'content_type' => 'pdf',
                'content_data' => [
                    'sections' => [
                        'Relacionamento Profissional' => 'Mantemos relações respeitosas e profissionais',
                        'Confidencialidade' => 'Protegemos informações confidenciais de clientes e da empresa',
                        'Conflitos de Interesse' => 'Identificamos e gerenciamos potenciais conflitos',
                        'Uso de Recursos' => 'Utilizamos recursos da empresa de forma responsável'
                    ]
                ],
                'order_index' => 2,
                'duration' => 600, // 10 minutos
                'file_path' => 'modules/documents/codigo-conduta-resumido.pdf',
                'mime_type' => 'application/pdf'
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }

    private function createHRContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Benefícios Oferecidos',
                'content_type' => 'interactive',
                'content_data' => [
                    'health_benefits' => [
                        'Plano de Saúde Bradesco (100% custeado pela empresa)',
                        'Plano Odontológico (100% custeado)',
                        'Seguro de Vida em Grupo'
                    ],
                    'financial_benefits' => [
                        'Vale Refeição: R$ 35/dia',
                        'Vale Alimentação: R$ 500/mês',
                        'Participação nos Lucros (PLR)',
                        'Previdência Privada com contrapartida'
                    ],
                    'work_life_balance' => [
                        'Horário Flexível (8h às 18h ou 9h às 19h)',
                        'Home Office 2x por semana',
                        'Férias de 30 dias',
                        'Licença Maternidade/Paternidade estendida'
                    ]
                ],
                'order_index' => 1,
                'duration' => 900, // 15 minutos
                'interactive_markers' => [
                    ['time' => 300, 'title' => 'Benefícios de Saúde', 'description' => 'Cuidando do seu bem-estar'],
                    ['time' => 600, 'title' => 'Benefícios Financeiros', 'description' => 'Apoio financeiro mensal']
                ]
            ],
            [
                'title' => 'Políticas de Desenvolvimento',
                'content_type' => 'text',
                'content_data' => [
                    'content' => 'Investimos no crescimento profissional de nossos colaboradores através de programas estruturados de desenvolvimento.',
                    'programs' => [
                        'Programa de Mentoria Interna',
                        'Cursos e Certificações Externas (custeados)',
                        'Participação em Congressos e Eventos',
                        'Programa de Idiomas',
                        'MBA Executivo (bolsa parcial)'
                    ]
                ],
                'order_index' => 2,
                'duration' => 480, // 8 minutos
                'notes_enabled' => true
            ],
            [
                'title' => 'Manual do Colaborador',
                'content_type' => 'pdf',
                'content_data' => [
                    'description' => 'Manual completo com todas as políticas e procedimentos de RH',
                    'pages' => 45,
                    'last_updated' => '2024-01-15'
                ],
                'order_index' => 3,
                'duration' => 1200, // 20 minutos
                'file_path' => 'modules/documents/manual-colaborador.pdf',
                'mime_type' => 'application/pdf',
                'file_size' => 2048000 // 2MB
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }

    private function createSecurityContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Os 5 Pilares da Segurança',
                'content_type' => 'interactive',
                'content_data' => [
                    'pillars' => [
                        'Confidencialidade' => 'Garantir que informações sejam acessadas apenas por pessoas autorizadas',
                        'Integridade' => 'Assegurar que dados não sejam alterados de forma não autorizada',
                        'Disponibilidade' => 'Manter sistemas e informações acessíveis quando necessário',
                        'Autenticidade' => 'Verificar a identidade de usuários e origem de informações',
                        'Não-repúdio' => 'Impedir que alguém negue ter realizado uma ação'
                    ]
                ],
                'order_index' => 1,
                'duration' => 1200, // 20 minutos
                'interactive_markers' => [
                    ['time' => 240, 'title' => 'Confidencialidade', 'description' => 'Protegendo informações sensíveis'],
                    ['time' => 480, 'title' => 'Integridade', 'description' => 'Garantindo dados íntegros'],
                    ['time' => 720, 'title' => 'Disponibilidade', 'description' => 'Sistemas sempre acessíveis'],
                    ['time' => 960, 'title' => 'Autenticidade', 'description' => 'Verificando identidades']
                ]
            ],
            [
                'title' => 'Simulação: Identificando Ameaças',
                'content_type' => 'interactive',
                'content_data' => [
                    'scenarios' => [
                        [
                            'title' => 'E-mail Suspeito',
                            'description' => 'Você recebe um e-mail urgente solicitando sua senha',
                            'correct_action' => 'Não responder e reportar ao TI',
                            'wrong_actions' => ['Responder com a senha', 'Ignorar completamente']
                        ],
                        [
                            'title' => 'Ligação de Suporte',
                            'description' => 'Alguém liga se passando por suporte técnico',
                            'correct_action' => 'Verificar identidade antes de fornecer informações',
                            'wrong_actions' => ['Fornecer dados imediatamente', 'Desligar sem verificar']
                        ]
                    ]
                ],
                'order_index' => 2,
                'duration' => 1800, // 30 minutos
                'interactive_markers' => [
                    ['time' => 600, 'title' => 'Cenário 1', 'description' => 'E-mail de phishing'],
                    ['time' => 1200, 'title' => 'Cenário 2', 'description' => 'Engenharia social por telefone']
                ]
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }

    private function createITContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Tour pelos Sistemas',
                'content_type' => 'video',
                'content_data' => [
                    'video_url' => 'https://example.com/videos/systems-tour.mp4',
                    'systems' => [
                        'Bloomberg Terminal' => 'Acesso a dados de mercado em tempo real',
                        'Sistema de Gestão (SGI)' => 'Controle de carteiras e operações',
                        'CRM Salesforce' => 'Gestão de relacionamento com clientes',
                        'Microsoft 365' => 'Ferramentas de produtividade e colaboração'
                    ]
                ],
                'order_index' => 1,
                'duration' => 900, // 15 minutos
                'transcript' => [
                    'Bem-vindos ao tour pelos nossos sistemas corporativos...',
                    'Primeiro, vamos conhecer o Bloomberg Terminal...',
                    'Agora, vamos explorar nosso Sistema de Gestão...'
                ]
            ],
            [
                'title' => 'Guia de Primeiros Passos',
                'content_type' => 'interactive',
                'content_data' => [
                    'steps' => [
                        'Login no sistema com suas credenciais',
                        'Configuração do perfil pessoal',
                        'Navegação pelos menus principais',
                        'Acesso aos relatórios básicos',
                        'Solicitação de suporte técnico'
                    ],
                    'tips' => [
                        'Sempre faça logout ao sair',
                        'Mantenha suas credenciais seguras',
                        'Use senhas fortes e únicas',
                        'Reporte problemas imediatamente'
                    ]
                ],
                'order_index' => 2,
                'duration' => 600, // 10 minutos
                'interactive_markers' => [
                    ['time' => 120, 'title' => 'Login Seguro', 'description' => 'Como fazer login corretamente'],
                    ['time' => 300, 'title' => 'Configuração', 'description' => 'Personalizando seu perfil'],
                    ['time' => 480, 'title' => 'Navegação', 'description' => 'Explorando os menus']
                ]
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }

    private function createProcessContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Fluxograma Interativo de Processos',
                'content_type' => 'interactive',
                'content_data' => [
                    'workflows' => [
                        'Solicitação de Férias' => [
                            'Funcionário solicita no sistema',
                            'Gestor direto aprova/rejeita',
                            'RH valida disponibilidade',
                            'Confirmação automática por e-mail'
                        ],
                        'Reembolso de Despesas' => [
                            'Upload de comprovantes no sistema',
                            'Gestor aprova categoria e valor',
                            'Financeiro valida documentação',
                            'Pagamento em até 5 dias úteis'
                        ],
                        'Solicitação de Compras' => [
                            'Requisição no sistema de compras',
                            'Aprovação do gestor da área',
                            'Cotação com fornecedores',
                            'Aprovação final e pedido'
                        ]
                    ]
                ],
                'order_index' => 1,
                'duration' => 900, // 15 minutos
                'interactive_markers' => [
                    ['time' => 300, 'title' => 'Processo de Férias', 'description' => 'Como solicitar férias'],
                    ['time' => 600, 'title' => 'Reembolsos', 'description' => 'Processo de reembolso']
                ]
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }

    private function createGenericContent(Module $module)
    {
        $contents = [
            [
                'title' => 'Introdução ao Módulo',
                'content_type' => 'text',
                'content_data' => [
                    'content' => 'Este módulo aborda conceitos importantes para sua integração na empresa. Dedique tempo para absorver o conteúdo e não hesite em fazer perguntas.',
                    'objectives' => [
                        'Compreender os conceitos fundamentais',
                        'Aplicar o conhecimento no dia a dia',
                        'Identificar situações práticas',
                        'Desenvolver competências específicas'
                    ]
                ],
                'order_index' => 1,
                'duration' => 300, // 5 minutos
                'notes_enabled' => true
            ],
            [
                'title' => 'Pontos-chave para Lembrar',
                'content_type' => 'text',
                'content_data' => [
                    'key_points' => [
                        'Sempre consulte as políticas da empresa',
                        'Em caso de dúvidas, procure seu gestor',
                        'Mantenha-se atualizado com as mudanças',
                        'Pratique os conceitos aprendidos',
                        'Compartilhe conhecimento com a equipe'
                    ]
                ],
                'order_index' => 2,
                'duration' => 180, // 3 minutos
                'notes_enabled' => true
            ]
        ];

        foreach ($contents as $contentData) {
            ModuleContent::create(array_merge($contentData, ['module_id' => $module->id]));
        }
    }
}