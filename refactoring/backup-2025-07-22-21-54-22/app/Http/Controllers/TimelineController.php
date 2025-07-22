<?php

namespace App\Http\Controllers;

use App\Models\TimelineEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimelineController extends Controller
{
    /**
     * Exibe a linha do tempo da empresa.
     */
    public function index(): View
    {
        $events = $this->getTimelineEvents();
        $categories = $this->getEventCategories();
        $stats = $this->getTimelineStats($events);
        
        return view('timeline.index', compact('events', 'categories', 'stats'));
    }

    /**
     * Exibe detalhes de um evento específico.
     */
    public function show(int $eventId): View
    {
        $event = collect($this->getTimelineEvents())->firstWhere('id', $eventId);
        
        if (!$event) {
            abort(404, 'Evento não encontrado');
        }
        
        return view('timeline.show', compact('event'));
    }

    /**
     * Filtra eventos por categoria.
     */
    public function filterByCategory(Request $request)
    {
        $category = $request->get('category');
        $events = collect($this->getTimelineEvents());
        
        if ($category && $category !== 'all') {
            $events = $events->where('category', $category);
        }
        
        return response()->json([
            'events' => $events->values(),
            'total' => $events->count()
        ]);
    }

    /**
     * Dados dos eventos da linha do tempo.
     */
    private function getTimelineEvents(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Fundação da Hemera Capital Partners',
                'date' => '2010-03-15',
                'year' => 2010,
                'category' => 'milestone',
                'importance' => 'high',
                'description' => 'Nascimento da Hemera Capital Partners com a visão de revolucionar o mercado de gestão de investimentos no Brasil.',
                'details' => 'A empresa foi fundada por um grupo de profissionais experientes do mercado financeiro, com o objetivo de oferecer soluções inovadoras em gestão de patrimônio e investimentos. Iniciamos com um capital de R$ 5 milhões e uma equipe de 8 pessoas.',
                'icon' => 'rocket',
                'color' => '#8B5CF6',
                'image' => '/images/timeline/fundacao.jpg',
                'achievements' => [
                    'Registro na CVM',
                    'Primeira sede no centro financeiro de SP',
                    'Equipe inicial de 8 profissionais'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Primeiro Cliente Institucional',
                'date' => '2010-08-22',
                'year' => 2010,
                'category' => 'client',
                'importance' => 'medium',
                'description' => 'Conquista do primeiro grande cliente institucional, marcando nossa entrada no mercado corporativo.',
                'details' => 'Fechamos nosso primeiro contrato com uma empresa de grande porte para gestão de seus recursos financeiros, totalizando R$ 50 milhões em ativos sob gestão.',
                'icon' => 'handshake',
                'color' => '#10B981',
                'image' => '/images/timeline/primeiro-cliente.jpg',
                'achievements' => [
                    'R$ 50 milhões em AUM',
                    'Primeiro contrato corporativo',
                    'Validação do modelo de negócio'
                ]
            ],
            [
                'id' => 3,
                'title' => 'Lançamento do Fundo HCP Alpha',
                'date' => '2012-06-10',
                'year' => 2012,
                'category' => 'product',
                'importance' => 'high',
                'description' => 'Criação do nosso primeiro fundo de investimento próprio, focado em estratégias de long-short equity.',
                'details' => 'O Fundo HCP Alpha foi desenvolvido com uma estratégia inovadora de hedge fund, combinando análise fundamentalista com técnicas quantitativas avançadas.',
                'icon' => 'chart-line',
                'color' => '#3B82F6',
                'image' => '/images/timeline/fundo-alpha.jpg',
                'achievements' => [
                    'Primeiro fundo próprio',
                    'Estratégia long-short equity',
                    'R$ 100 milhões captados no primeiro ano'
                ]
            ],
            [
                'id' => 4,
                'title' => 'Prêmio Melhor Gestora Emergente',
                'date' => '2013-11-28',
                'year' => 2013,
                'category' => 'award',
                'importance' => 'high',
                'description' => 'Reconhecimento como "Melhor Gestora Emergente" pela revista Investidor Institucional.',
                'details' => 'Este prêmio reconheceu nossa excelência em gestão de recursos e inovação em produtos financeiros, consolidando nossa posição no mercado.',
                'icon' => 'trophy',
                'color' => '#F59E0B',
                'image' => '/images/timeline/premio-2013.jpg',
                'achievements' => [
                    'Reconhecimento da indústria',
                    'Validação da estratégia',
                    'Aumento da credibilidade no mercado'
                ]
            ],
            [
                'id' => 5,
                'title' => 'Expansão para o Rio de Janeiro',
                'date' => '2015-04-18',
                'year' => 2015,
                'category' => 'expansion',
                'importance' => 'medium',
                'description' => 'Abertura do primeiro escritório regional no Rio de Janeiro, expandindo nossa presença nacional.',
                'details' => 'A expansão para o Rio de Janeiro foi estratégica para atender melhor nossos clientes da região sudeste e captar novos investidores institucionais.',
                'icon' => 'map-pin',
                'color' => '#EC4899',
                'image' => '/images/timeline/expansao-rj.jpg',
                'achievements' => [
                    'Primeiro escritório regional',
                    'Equipe de 15 pessoas no RJ',
                    'Aumento de 40% na base de clientes'
                ]
            ],
            [
                'id' => 6,
                'title' => 'Parceria Estratégica Internacional',
                'date' => '2016-09-12',
                'year' => 2016,
                'category' => 'partnership',
                'importance' => 'high',
                'description' => 'Estabelecimento de parceria com gestora americana para acesso a mercados internacionais.',
                'details' => 'Firmamos parceria com a Wellington Management para oferecer produtos internacionais aos nossos clientes e expandir nosso conhecimento em mercados globais.',
                'icon' => 'globe',
                'color' => '#6366F1',
                'image' => '/images/timeline/parceria-internacional.jpg',
                'achievements' => [
                    'Acesso a mercados internacionais',
                    'Transferência de conhecimento',
                    'Novos produtos para clientes'
                ]
            ],
            [
                'id' => 7,
                'title' => 'Lançamento da Plataforma Digital',
                'date' => '2018-01-25',
                'year' => 2018,
                'category' => 'innovation',
                'importance' => 'high',
                'description' => 'Desenvolvimento e lançamento da nossa plataforma digital proprietária para gestão de investimentos.',
                'details' => 'A plataforma HCP Digital revolucionou a forma como nossos clientes acompanham seus investimentos, oferecendo transparência total e relatórios em tempo real.',
                'icon' => 'computer',
                'color' => '#14B8A6',
                'image' => '/images/timeline/plataforma-digital.jpg',
                'achievements' => [
                    'Tecnologia proprietária',
                    'Transparência total para clientes',
                    'Relatórios em tempo real'
                ]
            ],
            [
                'id' => 8,
                'title' => 'Certificação B Corp',
                'date' => '2019-07-08',
                'year' => 2019,
                'category' => 'sustainability',
                'importance' => 'medium',
                'description' => 'Conquista da certificação B Corp, reconhecendo nosso compromisso com sustentabilidade e impacto social.',
                'details' => 'Tornamo-nos uma das primeiras gestoras brasileiras a obter a certificação B Corp, demonstrando nosso compromisso com práticas ESG.',
                'icon' => 'leaf',
                'color' => '#22C55E',
                'image' => '/images/timeline/b-corp.jpg',
                'achievements' => [
                    'Primeira gestora B Corp do Brasil',
                    'Compromisso com ESG',
                    'Impacto social positivo'
                ]
            ],
            [
                'id' => 9,
                'title' => 'R$ 1 Bilhão em Ativos',
                'date' => '2020-12-31',
                'year' => 2020,
                'category' => 'milestone',
                'importance' => 'high',
                'description' => 'Marco histórico de R$ 1 bilhão em ativos sob gestão, consolidando nossa posição no mercado.',
                'details' => 'Atingimos a marca de R$ 1 bilhão em ativos sob gestão, resultado de 10 anos de crescimento consistente e confiança dos nossos investidores.',
                'icon' => 'currency-dollar',
                'color' => '#8B5CF6',
                'image' => '/images/timeline/1-bilhao.jpg',
                'achievements' => [
                    'R$ 1 bilhão em AUM',
                    '10 anos de crescimento',
                    'Consolidação no mercado'
                ]
            ],
            [
                'id' => 10,
                'title' => 'Programa de Diversidade e Inclusão',
                'date' => '2021-03-08',
                'year' => 2021,
                'category' => 'culture',
                'importance' => 'medium',
                'description' => 'Lançamento do programa "HCP Diversa" para promover diversidade e inclusão na empresa.',
                'details' => 'Implementamos políticas abrangentes de diversidade, incluindo cotas para contratação, programas de mentoria e iniciativas de inclusão.',
                'icon' => 'users-group',
                'color' => '#EC4899',
                'image' => '/images/timeline/diversidade.jpg',
                'achievements' => [
                    '40% de mulheres na liderança',
                    'Programa de mentoria',
                    'Políticas de inclusão'
                ]
            ],
            [
                'id' => 11,
                'title' => 'Fundo de Venture Capital',
                'date' => '2022-05-20',
                'year' => 2022,
                'category' => 'product',
                'importance' => 'high',
                'description' => 'Criação do HCP Ventures, nosso fundo dedicado a investimentos em startups de tecnologia financeira.',
                'details' => 'Expandimos nossa atuação para o mercado de venture capital, focando em fintechs e empresas de tecnologia com potencial disruptivo.',
                'icon' => 'lightbulb',
                'color' => '#F59E0B',
                'image' => '/images/timeline/venture-capital.jpg',
                'achievements' => [
                    'Entrada no mercado de VC',
                    'Foco em fintechs',
                    'R$ 200 milhões para investimento'
                ]
            ],
            [
                'id' => 12,
                'title' => 'Expansão Internacional',
                'date' => '2023-08-15',
                'year' => 2023,
                'category' => 'expansion',
                'importance' => 'high',
                'description' => 'Abertura do primeiro escritório internacional em Miami, expandindo para o mercado americano.',
                'details' => 'Nossa expansão para Miami marca o início da nossa atuação internacional, oferecendo serviços para brasileiros no exterior e investidores americanos.',
                'icon' => 'airplane',
                'color' => '#3B82F6',
                'image' => '/images/timeline/miami.jpg',
                'achievements' => [
                    'Primeiro escritório internacional',
                    'Mercado americano',
                    'Serviços para brasileiros no exterior'
                ]
            ],
            [
                'id' => 13,
                'title' => 'Prêmio ESG Excellence',
                'date' => '2024-02-10',
                'year' => 2024,
                'category' => 'award',
                'importance' => 'medium',
                'description' => 'Reconhecimento internacional pelo nosso compromisso com práticas ESG e investimento sustentável.',
                'details' => 'Fomos premiados pela nossa liderança em investimentos ESG e pelo impacto positivo gerado através dos nossos fundos sustentáveis.',
                'icon' => 'award',
                'color' => '#22C55E',
                'image' => '/images/timeline/esg-award.jpg',
                'achievements' => [
                    'Reconhecimento internacional',
                    'Liderança em ESG',
                    'Impacto positivo mensurável'
                ]
            ],
            [
                'id' => 14,
                'title' => 'Plataforma de IA para Investimentos',
                'date' => '2024-06-30',
                'year' => 2024,
                'category' => 'innovation',
                'importance' => 'high',
                'description' => 'Lançamento da HCP AI, nossa plataforma de inteligência artificial para análise e gestão de investimentos.',
                'details' => 'Desenvolvemos uma plataforma proprietária de IA que utiliza machine learning para otimizar estratégias de investimento e análise de risco.',
                'icon' => 'cpu-chip',
                'color' => '#6366F1',
                'image' => '/images/timeline/ai-platform.jpg',
                'achievements' => [
                    'Tecnologia de IA proprietária',
                    'Machine learning aplicado',
                    'Otimização de estratégias'
                ]
            ]
        ];
    }

    /**
     * Categorias de eventos.
     */
    private function getEventCategories(): array
    {
        return [
            'milestone' => [
                'name' => 'Marcos Históricos',
                'color' => '#8B5CF6',
                'icon' => 'flag'
            ],
            'product' => [
                'name' => 'Produtos e Serviços',
                'color' => '#3B82F6',
                'icon' => 'cube'
            ],
            'award' => [
                'name' => 'Prêmios e Reconhecimentos',
                'color' => '#F59E0B',
                'icon' => 'trophy'
            ],
            'expansion' => [
                'name' => 'Expansão',
                'color' => '#EC4899',
                'icon' => 'map'
            ],
            'partnership' => [
                'name' => 'Parcerias',
                'color' => '#6366F1',
                'icon' => 'handshake'
            ],
            'innovation' => [
                'name' => 'Inovação',
                'color' => '#14B8A6',
                'icon' => 'lightbulb'
            ],
            'sustainability' => [
                'name' => 'Sustentabilidade',
                'color' => '#22C55E',
                'icon' => 'leaf'
            ],
            'culture' => [
                'name' => 'Cultura e Pessoas',
                'color' => '#EC4899',
                'icon' => 'heart'
            ],
            'client' => [
                'name' => 'Clientes',
                'color' => '#10B981',
                'icon' => 'users'
            ]
        ];
    }

    /**
     * Estatísticas da linha do tempo.
     */
    private function getTimelineStats(array $events): array
    {
        $eventsByYear = collect($events)->groupBy('year');
        $eventsByCategory = collect($events)->groupBy('category');
        
        return [
            'total_events' => count($events),
            'years_active' => $eventsByYear->keys()->max() - $eventsByYear->keys()->min() + 1,
            'categories_count' => $eventsByCategory->count(),
            'major_milestones' => collect($events)->where('importance', 'high')->count(),
            'recent_events' => collect($events)->where('year', '>=', date('Y') - 2)->count(),
            'events_by_year' => $eventsByYear->map->count(),
            'events_by_category' => $eventsByCategory->map->count()
        ];
    }
}