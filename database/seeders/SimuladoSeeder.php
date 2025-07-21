<?php

namespace Database\Seeders;

use App\Models\Simulado;
use App\Models\SimuladoQuestao;
use Illuminate\Database\Seeder;

class SimuladoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Simulado 1: Fundamentos de Tecnologia
        $simulado1 = Simulado::create([
            'titulo' => 'Fundamentos de Tecnologia',
            'descricao' => 'Este simulado avalia seus conhecimentos básicos sobre tecnologia, incluindo conceitos de programação, bancos de dados e infraestrutura.',
            'categoria' => 'technical',
            'nivel' => 'basic',
            'duracao' => 30, // minutos
            'questoes_count' => 10,
            'passing_score' => 70,
            'pontos_recompensa' => 100,
            'status' => 'active',
        ]);

        // Questões para o Simulado 1
        $questoes1 = [
            [
                'pergunta' => 'O que é um algoritmo?',
                'opcoes' => [
                    'A' => 'Um tipo de linguagem de programação',
                    'B' => 'Um conjunto de instruções para resolver um problema',
                    'C' => 'Um componente de hardware',
                    'D' => 'Um tipo de banco de dados'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Um algoritmo é um conjunto finito e ordenado de passos para resolver um problema específico.',
                'pontos' => 1,
                'ordem' => 1,
                'dificuldade' => 'facil'
            ],
            [
                'pergunta' => 'O que é um banco de dados relacional?',
                'opcoes' => [
                    'A' => 'Um banco de dados que armazena apenas números',
                    'B' => 'Um banco de dados que armazena dados em documentos',
                    'C' => 'Um banco de dados que organiza dados em tabelas com relações entre elas',
                    'D' => 'Um banco de dados que só funciona online'
                ],
                'resposta_correta' => 'C',
                'explicacao' => 'Um banco de dados relacional organiza os dados em tabelas (relações) que podem ser relacionadas entre si através de chaves.',
                'pontos' => 1,
                'ordem' => 2,
                'dificuldade' => 'facil'
            ],
            [
                'pergunta' => 'O que significa a sigla HTML?',
                'opcoes' => [
                    'A' => 'Hyper Text Markup Language',
                    'B' => 'High Tech Modern Language',
                    'C' => 'Hyper Transfer Module Language',
                    'D' => 'Home Tool Markup Language'
                ],
                'resposta_correta' => 'A',
                'explicacao' => 'HTML significa Hyper Text Markup Language (Linguagem de Marcação de Hipertexto), que é a linguagem padrão para criar páginas web.',
                'pontos' => 1,
                'ordem' => 3,
                'dificuldade' => 'facil'
            ],
            [
                'pergunta' => 'O que é uma API?',
                'opcoes' => [
                    'A' => 'Um tipo de linguagem de programação',
                    'B' => 'Um conjunto de regras que permite que diferentes softwares se comuniquem',
                    'C' => 'Um componente de hardware',
                    'D' => 'Um tipo de banco de dados'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'API (Application Programming Interface) é um conjunto de regras e protocolos que permite que diferentes softwares se comuniquem entre si.',
                'pontos' => 1,
                'ordem' => 4,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um servidor web?',
                'opcoes' => [
                    'A' => 'Um computador que hospeda sites e aplicações web',
                    'B' => 'Um programa que cria sites',
                    'C' => 'Um dispositivo que conecta computadores em rede',
                    'D' => 'Um tipo de banco de dados'
                ],
                'resposta_correta' => 'A',
                'explicacao' => 'Um servidor web é um computador que armazena, processa e entrega páginas web aos clientes (navegadores) que as solicitam.',
                'pontos' => 1,
                'ordem' => 5,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um framework?',
                'opcoes' => [
                    'A' => 'Um tipo de linguagem de programação',
                    'B' => 'Uma estrutura que serve como base para desenvolvimento de software',
                    'C' => 'Um componente de hardware',
                    'D' => 'Um tipo de banco de dados'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Um framework é uma estrutura que serve como base para o desenvolvimento de software, fornecendo funcionalidades comuns que podem ser reutilizadas.',
                'pontos' => 1,
                'ordem' => 6,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um sistema operacional?',
                'opcoes' => [
                    'A' => 'Um software que gerencia hardware e fornece serviços para programas',
                    'B' => 'Um programa para criar documentos',
                    'C' => 'Um dispositivo que conecta computadores em rede',
                    'D' => 'Um tipo de banco de dados'
                ],
                'resposta_correta' => 'A',
                'explicacao' => 'Um sistema operacional é um software que gerencia os recursos de hardware e fornece serviços comuns para programas de computador.',
                'pontos' => 1,
                'ordem' => 7,
                'dificuldade' => 'facil'
            ],
            [
                'pergunta' => 'O que é computação em nuvem?',
                'opcoes' => [
                    'A' => 'Usar computadores apenas em dias nublados',
                    'B' => 'Armazenar dados em servidores físicos locais',
                    'C' => 'Usar recursos computacionais entregues como serviço pela internet',
                    'D' => 'Um tipo de sistema operacional'
                ],
                'resposta_correta' => 'C',
                'explicacao' => 'Computação em nuvem é o fornecimento de recursos computacionais (servidores, armazenamento, bancos de dados, rede, software) pela internet ("a nuvem").',
                'pontos' => 1,
                'ordem' => 8,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um firewall?',
                'opcoes' => [
                    'A' => 'Um dispositivo de hardware ou software que monitora e filtra o tráfego de rede',
                    'B' => 'Um tipo de vírus de computador',
                    'C' => 'Um programa para criar sites',
                    'D' => 'Um tipo de banco de dados'
                ],
                'resposta_correta' => 'A',
                'explicacao' => 'Um firewall é um sistema de segurança que monitora e controla o tráfego de rede de entrada e saída com base em regras de segurança predeterminadas.',
                'pontos' => 1,
                'ordem' => 9,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um bug?',
                'opcoes' => [
                    'A' => 'Um recurso planejado em um programa',
                    'B' => 'Um erro ou falha em um programa de computador',
                    'C' => 'Um tipo de vírus',
                    'D' => 'Um componente de hardware'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Um bug é um erro, falha ou defeito em um programa de computador que faz com que ele produza um resultado incorreto, inesperado ou se comporte de maneira não pretendida.',
                'pontos' => 1,
                'ordem' => 10,
                'dificuldade' => 'facil'
            ],
        ];

        foreach ($questoes1 as $questao) {
            SimuladoQuestao::create([
                'simulado_id' => $simulado1->id,
                'pergunta' => $questao['pergunta'],
                'opcoes' => $questao['opcoes'],
                'resposta_correta' => $questao['resposta_correta'],
                'explicacao' => $questao['explicacao'],
                'pontos' => $questao['pontos'],
                'ordem' => $questao['ordem'],
                'dificuldade' => $questao['dificuldade'],
            ]);
        }

        // Simulado 2: Segurança da Informação
        $simulado2 = Simulado::create([
            'titulo' => 'Segurança da Informação',
            'descricao' => 'Este simulado avalia seus conhecimentos sobre segurança da informação, incluindo conceitos de criptografia, proteção de dados e prevenção de ameaças.',
            'categoria' => 'security',
            'nivel' => 'intermediate',
            'duracao' => 45, // minutos
            'questoes_count' => 10,
            'passing_score' => 75,
            'pontos_recompensa' => 150,
            'status' => 'active',
        ]);

        // Questões para o Simulado 2
        $questoes2 = [
            [
                'pergunta' => 'O que é criptografia?',
                'opcoes' => [
                    'A' => 'Um tipo de vírus de computador',
                    'B' => 'Um processo de converter dados em um formato ilegível para proteger informações',
                    'C' => 'Um método para acelerar a conexão com a internet',
                    'D' => 'Um tipo de firewall'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Criptografia é o processo de converter informações ou dados em um código para prevenir acesso não autorizado.',
                'pontos' => 1,
                'ordem' => 1,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um ataque de phishing?',
                'opcoes' => [
                    'A' => 'Um ataque que sobrecarrega servidores com tráfego',
                    'B' => 'Um ataque que explora vulnerabilidades em software',
                    'C' => 'Um ataque que tenta enganar usuários para revelar informações sensíveis',
                    'D' => 'Um ataque que desativa firewalls'
                ],
                'resposta_correta' => 'C',
                'explicacao' => 'Phishing é uma técnica de engenharia social onde atacantes se passam por entidades confiáveis para enganar as vítimas e obter informações sensíveis como senhas e dados de cartão de crédito.',
                'pontos' => 1,
                'ordem' => 2,
                'dificuldade' => 'facil'
            ],
            [
                'pergunta' => 'O que é autenticação de dois fatores (2FA)?',
                'opcoes' => [
                    'A' => 'Um método que requer duas senhas diferentes',
                    'B' => 'Um método que requer dois tipos diferentes de verificação para acesso',
                    'C' => 'Um método que usa dois servidores diferentes',
                    'D' => 'Um método que requer dois usuários diferentes'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Autenticação de dois fatores (2FA) é um método de segurança que requer dois tipos diferentes de verificação para confirmar a identidade do usuário, geralmente algo que você sabe (senha) e algo que você tem (como um código enviado ao celular).',
                'pontos' => 1,
                'ordem' => 3,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um ransomware?',
                'opcoes' => [
                    'A' => 'Um software que protege contra vírus',
                    'B' => 'Um tipo de firewall',
                    'C' => 'Um malware que criptografa dados e exige pagamento para descriptografá-los',
                    'D' => 'Um protocolo de segurança para redes'
                ],
                'resposta_correta' => 'C',
                'explicacao' => 'Ransomware é um tipo de malware que criptografa os arquivos da vítima e exige um pagamento (resgate) para fornecer a chave de descriptografia.',
                'pontos' => 1,
                'ordem' => 4,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é uma VPN?',
                'opcoes' => [
                    'A' => 'Um tipo de vírus',
                    'B' => 'Uma rede privada virtual que cria uma conexão segura pela internet',
                    'C' => 'Um tipo de firewall',
                    'D' => 'Um protocolo de transferência de arquivos'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'VPN (Virtual Private Network) é uma tecnologia que cria uma conexão criptografada pela internet, permitindo que usuários acessem recursos de rede de forma segura como se estivessem conectados diretamente à rede privada.',
                'pontos' => 1,
                'ordem' => 5,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um ataque de força bruta?',
                'opcoes' => [
                    'A' => 'Um ataque que tenta todas as combinações possíveis para descobrir senhas',
                    'B' => 'Um ataque que usa engenharia social',
                    'C' => 'Um ataque que explora vulnerabilidades em software',
                    'D' => 'Um ataque que sobrecarrega servidores com tráfego'
                ],
                'resposta_correta' => 'A',
                'explicacao' => 'Um ataque de força bruta é um método de tentativa e erro usado para descobrir senhas ou chaves de criptografia tentando sistematicamente todas as combinações possíveis até encontrar a correta.',
                'pontos' => 1,
                'ordem' => 6,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um certificado SSL/TLS?',
                'opcoes' => [
                    'A' => 'Um documento que comprova a formação acadêmica',
                    'B' => 'Um arquivo digital que verifica a identidade de um site e permite conexões criptografadas',
                    'C' => 'Um tipo de firewall',
                    'D' => 'Um protocolo de transferência de arquivos'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Um certificado SSL/TLS é um arquivo digital que estabelece a identidade de um site e permite conexões criptografadas entre o servidor web e o navegador do usuário.',
                'pontos' => 1,
                'ordem' => 7,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é engenharia social no contexto de segurança da informação?',
                'opcoes' => [
                    'A' => 'Um ramo da engenharia civil',
                    'B' => 'O uso de manipulação psicológica para enganar pessoas e obter informações confidenciais',
                    'C' => 'Um método para projetar redes seguras',
                    'D' => 'Um tipo de firewall'
                ],
                'resposta_correta' => 'B',
                'explicacao' => 'Engenharia social é a manipulação psicológica de pessoas para que realizem ações ou divulguem informações confidenciais. Em vez de explorar vulnerabilidades técnicas, explora a natureza humana.',
                'pontos' => 1,
                'ordem' => 8,
                'dificuldade' => 'facil'
            ],
            [
                'pergunta' => 'O que é um ataque DDoS?',
                'opcoes' => [
                    'A' => 'Um ataque que tenta todas as combinações possíveis para descobrir senhas',
                    'B' => 'Um ataque que usa engenharia social',
                    'C' => 'Um ataque que explora vulnerabilidades em software',
                    'D' => 'Um ataque que sobrecarrega servidores com tráfego de múltiplas fontes'
                ],
                'resposta_correta' => 'D',
                'explicacao' => 'DDoS (Distributed Denial of Service) é um ataque cibernético que tenta tornar um serviço online indisponível sobrecarregando-o com tráfego de múltiplas fontes.',
                'pontos' => 1,
                'ordem' => 9,
                'dificuldade' => 'medio'
            ],
            [
                'pergunta' => 'O que é um backup?',
                'opcoes' => [
                    'A' => 'Uma cópia de dados armazenada em outro local como precaução',
                    'B' => 'Um tipo de firewall',
                    'C' => 'Um método para acelerar a conexão com a internet',
                    'D' => 'Um tipo de vírus'
                ],
                'resposta_correta' => 'A',
                'explicacao' => 'Backup é uma cópia de dados que pode ser usada para restaurar os dados originais em caso de perda, corrupção ou ataque cibernético.',
                'pontos' => 1,
                'ordem' => 10,
                'dificuldade' => 'facil'
            ],
        ];

        foreach ($questoes2 as $questao) {
            SimuladoQuestao::create([
                'simulado_id' => $simulado2->id,
                'pergunta' => $questao['pergunta'],
                'opcoes' => $questao['opcoes'],
                'resposta_correta' => $questao['resposta_correta'],
                'explicacao' => $questao['explicacao'],
                'pontos' => $questao['pontos'],
                'ordem' => $questao['ordem'],
                'dificuldade' => $questao['dificuldade'],
            ]);
        }
    }
}