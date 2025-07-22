<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class CompanyMapController extends Controller
{
    /**
     * Exibe o mapa interativo da empresa.
     */
    public function index(): View
    {
        $departments = $this->getDepartmentsData();
        $floors = $this->getFloorsData();
        
        return view('company-map.index', compact('departments', 'floors'));
    }

    /**
     * Retorna dados de um departamento específico via AJAX.
     */
    public function getDepartmentInfo(Request $request)
    {
        $departmentId = $request->get('department');
        $departments = $this->getDepartmentsData();
        
        $department = collect($departments)->firstWhere('id', $departmentId);
        
        if (!$department) {
            return response()->json(['error' => 'Departamento não encontrado'], 404);
        }
        
        return response()->json($department);
    }

    /**
     * Dados dos departamentos da empresa.
     */
    private function getDepartmentsData(): array
    {
        return [
            [
                'id' => 'diretoria',
                'name' => 'Diretoria Executiva',
                'floor' => 2,
                'position' => ['x' => 50, 'y' => 20],
                'size' => ['width' => 25, 'height' => 15],
                'color' => '#8B5CF6',
                'icon' => 'crown',
                'leader' => [
                    'name' => 'Carlos Eduardo Silva',
                    'position' => 'CEO',
                    'email' => 'carlos.silva@hcp.com',
                    'phone' => '+55 11 99999-0001',
                    'avatar' => 'https://ui-avatars.com/api/?name=Carlos+Eduardo+Silva&color=8B5CF6&background=F3F4F6'
                ],
                'team_count' => 3,
                'description' => 'Responsável pela estratégia geral da empresa, tomada de decisões executivas e direcionamento dos negócios.',
                'objectives' => [
                    'Definir estratégia corporativa',
                    'Supervisionar operações gerais',
                    'Relacionamento com investidores',
                    'Governança corporativa'
                ],
                'functions' => [
                    'Planejamento estratégico',
                    'Gestão de stakeholders',
                    'Aprovação de investimentos',
                    'Liderança organizacional'
                ]
            ],
            [
                'id' => 'investimentos',
                'name' => 'Gestão de Investimentos',
                'floor' => 2,
                'position' => ['x' => 15, 'y' => 40],
                'size' => ['width' => 30, 'height' => 20],
                'color' => '#10B981',
                'icon' => 'trending-up',
                'leader' => [
                    'name' => 'Ana Paula Rodrigues',
                    'position' => 'Diretora de Investimentos',
                    'email' => 'ana.rodrigues@hcp.com',
                    'phone' => '+55 11 99999-0002',
                    'avatar' => 'https://ui-avatars.com/api/?name=Ana+Paula+Rodrigues&color=10B981&background=F3F4F6'
                ],
                'team_count' => 12,
                'description' => 'Equipe responsável pela análise, seleção e gestão dos investimentos da carteira de clientes.',
                'objectives' => [
                    'Maximizar retorno dos investimentos',
                    'Diversificar portfólios',
                    'Análise de mercado',
                    'Gestão de risco'
                ],
                'functions' => [
                    'Análise fundamentalista',
                    'Gestão de portfólio',
                    'Research de mercado',
                    'Alocação de ativos'
                ]
            ],
            [
                'id' => 'risco',
                'name' => 'Análise de Risco',
                'floor' => 2,
                'position' => ['x' => 55, 'y' => 40],
                'size' => ['width' => 25, 'height' => 20],
                'color' => '#F59E0B',
                'icon' => 'shield',
                'leader' => [
                    'name' => 'Roberto Santos',
                    'position' => 'Gerente de Risco',
                    'email' => 'roberto.santos@hcp.com',
                    'phone' => '+55 11 99999-0003',
                    'avatar' => 'https://ui-avatars.com/api/?name=Roberto+Santos&color=F59E0B&background=F3F4F6'
                ],
                'team_count' => 8,
                'description' => 'Departamento focado na identificação, análise e mitigação de riscos nos investimentos.',
                'objectives' => [
                    'Identificar riscos potenciais',
                    'Desenvolver modelos de risco',
                    'Monitorar exposições',
                    'Compliance regulatório'
                ],
                'functions' => [
                    'Modelagem de risco',
                    'Stress testing',
                    'Monitoramento contínuo',
                    'Relatórios regulatórios'
                ]
            ],
            [
                'id' => 'relacionamento',
                'name' => 'Relacionamento com Clientes',
                'floor' => 1,
                'position' => ['x' => 20, 'y' => 25],
                'size' => ['width' => 35, 'height' => 25],
                'color' => '#3B82F6',
                'icon' => 'users',
                'leader' => [
                    'name' => 'Mariana Costa',
                    'position' => 'Diretora Comercial',
                    'email' => 'mariana.costa@hcp.com',
                    'phone' => '+55 11 99999-0004',
                    'avatar' => 'https://ui-avatars.com/api/?name=Mariana+Costa&color=3B82F6&background=F3F4F6'
                ],
                'team_count' => 15,
                'description' => 'Equipe dedicada ao atendimento, relacionamento e desenvolvimento de negócios com clientes.',
                'objectives' => [
                    'Satisfação do cliente',
                    'Crescimento da carteira',
                    'Retenção de clientes',
                    'Novos negócios'
                ],
                'functions' => [
                    'Atendimento personalizado',
                    'Consultoria financeira',
                    'Prospecção de clientes',
                    'Gestão de relacionamento'
                ]
            ],
            [
                'id' => 'operacoes',
                'name' => 'Operações e TI',
                'floor' => 1,
                'position' => ['x' => 60, 'y' => 25],
                'size' => ['width' => 30, 'height' => 25],
                'color' => '#6366F1',
                'icon' => 'server',
                'leader' => [
                    'name' => 'Fernando Lima',
                    'position' => 'Diretor de Operações',
                    'email' => 'fernando.lima@hcp.com',
                    'phone' => '+55 11 99999-0005',
                    'avatar' => 'https://ui-avatars.com/api/?name=Fernando+Lima&color=6366F1&background=F3F4F6'
                ],
                'team_count' => 10,
                'description' => 'Responsável pela infraestrutura tecnológica e processos operacionais da empresa.',
                'objectives' => [
                    'Eficiência operacional',
                    'Segurança da informação',
                    'Inovação tecnológica',
                    'Suporte aos negócios'
                ],
                'functions' => [
                    'Desenvolvimento de sistemas',
                    'Infraestrutura de TI',
                    'Segurança cibernética',
                    'Automação de processos'
                ]
            ],
            [
                'id' => 'rh',
                'name' => 'Recursos Humanos',
                'floor' => 1,
                'position' => ['x' => 15, 'y' => 60],
                'size' => ['width' => 25, 'height' => 20],
                'color' => '#EC4899',
                'icon' => 'heart',
                'leader' => [
                    'name' => 'Juliana Oliveira',
                    'position' => 'Gerente de RH',
                    'email' => 'juliana.oliveira@hcp.com',
                    'phone' => '+55 11 99999-0006',
                    'avatar' => 'https://ui-avatars.com/api/?name=Juliana+Oliveira&color=EC4899&background=F3F4F6'
                ],
                'team_count' => 5,
                'description' => 'Departamento responsável pela gestão de pessoas, cultura organizacional e desenvolvimento humano.',
                'objectives' => [
                    'Desenvolvimento de talentos',
                    'Cultura organizacional',
                    'Bem-estar dos funcionários',
                    'Atração de talentos'
                ],
                'functions' => [
                    'Recrutamento e seleção',
                    'Treinamento e desenvolvimento',
                    'Gestão de performance',
                    'Benefícios e remuneração'
                ]
            ],
            [
                'id' => 'compliance',
                'name' => 'Compliance e Jurídico',
                'floor' => 1,
                'position' => ['x' => 50, 'y' => 60],
                'size' => ['width' => 30, 'height' => 20],
                'color' => '#DC2626',
                'icon' => 'scale',
                'leader' => [
                    'name' => 'Dr. Paulo Mendes',
                    'position' => 'Diretor Jurídico',
                    'email' => 'paulo.mendes@hcp.com',
                    'phone' => '+55 11 99999-0007',
                    'avatar' => 'https://ui-avatars.com/api/?name=Paulo+Mendes&color=DC2626&background=F3F4F6'
                ],
                'team_count' => 6,
                'description' => 'Área responsável pela conformidade regulatória e questões jurídicas da empresa.',
                'objectives' => [
                    'Conformidade regulatória',
                    'Gestão de riscos legais',
                    'Políticas internas',
                    'Relacionamento regulatório'
                ],
                'functions' => [
                    'Compliance regulatório',
                    'Auditoria interna',
                    'Políticas e procedimentos',
                    'Gestão de contratos'
                ]
            ]
        ];
    }

    /**
     * Dados dos andares da empresa.
     */
    private function getFloorsData(): array
    {
        return [
            [
                'id' => 1,
                'name' => '1º Andar',
                'description' => 'Atendimento ao cliente, operações e suporte',
                'departments' => ['relacionamento', 'operacoes', 'rh', 'compliance']
            ],
            [
                'id' => 2,
                'name' => '2º Andar',
                'description' => 'Gestão executiva e investimentos',
                'departments' => ['diretoria', 'investimentos', 'risco']
            ]
        ];
    }
}