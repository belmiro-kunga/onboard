<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizQuestionExplanationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dados de explicações para as questões
        $explanations = [
            // 1. Fundamentos do Laravel
            [
                'question' => 'Qual é o comando para criar um novo projeto Laravel?',
                'explanation_correct' => 'O comando `composer create-project laravel/laravel projeto` é a forma oficial recomendada para criar um novo projeto Laravel utilizando o Composer, que é o gerenciador de dependências do PHP.',
                'explanation_incorrect' => [
                    'A' => 'O comando `laravel new projeto` funciona apenas se você tiver o instalador Laravel instalado globalmente, mas não é o método padrão usando Composer.',
                    'C' => 'O comando `php artisan new projeto` não existe. O comando artisan é usado para operações dentro de um projeto Laravel já existente.',
                    'D' => 'O npm é um gerenciador de pacotes para JavaScript, não para PHP ou Laravel.',
                    'default' => 'O comando correto para criar um novo projeto Laravel usando o Composer é `composer create-project laravel/laravel projeto`.'
                ]
            ],
            [
                'question' => 'O que é o Eloquent ORM?',
                'explanation_correct' => 'O Eloquent ORM (Object-Relational Mapping) é uma implementação avançada do padrão ActiveRecord que permite interagir com o banco de dados usando modelos orientados a objetos, tornando as consultas e relacionamentos mais intuitivos e expressivos.',
                'explanation_incorrect' => [
                    'A' => 'O Eloquent não é um framework JavaScript, mas sim um componente do Laravel para interação com banco de dados.',
                    'B' => 'O Eloquent não é um sistema de templates. O Blade é o sistema de templates do Laravel.',
                    'D' => 'O Eloquent não é um serviço de autenticação. O Laravel possui um sistema de autenticação separado.',
                    'default' => 'O Eloquent é o ORM (Object-Relational Mapping) do Laravel que facilita a interação com o banco de dados.'
                ]
            ],
            [
                'question' => 'Como definir uma rota no Laravel?',
                'explanation_correct' => 'No Laravel, as rotas são definidas no arquivo `web.php` na pasta routes. Este arquivo contém todas as rotas da aplicação web que respondem a requisições HTTP.',
                'explanation_incorrect' => [
                    'A' => 'O arquivo `routes.php` era usado em versões antigas do Laravel (antes da versão 5.3). Nas versões atuais, as rotas são separadas em diferentes arquivos na pasta routes.',
                    'C' => 'Não existe o comando `php artisan make:route`. As rotas são definidas manualmente nos arquivos de rotas.',
                    'D' => 'O arquivo `.htaccess` é usado para configurações do servidor Apache, não para definir rotas do Laravel.',
                    'default' => 'As rotas no Laravel são definidas no arquivo `web.php` na pasta routes.'
                ]
            ],
            [
                'question' => 'Qual é a função do arquivo .env?',
                'explanation_correct' => 'O arquivo .env armazena variáveis de ambiente e configurações específicas para cada ambiente de execução, como credenciais de banco de dados, chaves de API e outras configurações sensíveis que não devem ser versionadas.',
                'explanation_incorrect' => [
                    'A' => 'O arquivo .env não armazena código JavaScript. Para isso, usamos arquivos .js na pasta public ou resources.',
                    'B' => 'As rotas são definidas nos arquivos da pasta routes, não no .env.',
                    'D' => 'A estrutura do banco de dados é definida através de migrations, não no arquivo .env.',
                    'default' => 'O arquivo .env armazena variáveis de ambiente e configurações que variam entre ambientes de execução.'
                ]
            ],
            [
                'question' => 'O que são Migrations no Laravel?',
                'explanation_correct' => 'Migrations são como um sistema de controle de versão para o banco de dados, permitindo que a equipe modifique e compartilhe o esquema do banco de dados da aplicação de forma consistente e versionada.',
                'explanation_incorrect' => [
                    'B' => 'Migrations não são usadas para migrar dados entre servidores, mas sim para versionar a estrutura do banco de dados.',
                    'C' => 'Migrations não são um sistema de templates. O Blade é o sistema de templates do Laravel.',
                    'D' => 'Migrations não são usadas para criar controllers, mas sim para definir a estrutura do banco de dados.',
                    'default' => 'Migrations são um sistema de controle de versão para o banco de dados no Laravel.'
                ]
            ],
            
            // 2. Segurança da Informação
            [
                'question' => 'O que é phishing?',
                'explanation_correct' => 'Phishing é uma técnica de engenharia social onde atacantes se passam por entidades confiáveis para enganar vítimas e obter informações sensíveis como senhas, dados bancários ou informações pessoais, geralmente através de emails, mensagens ou sites falsos.',
                'explanation_incorrect' => [
                    'A' => 'Phishing não é um tipo de firewall, mas sim uma técnica de ataque que explora a engenharia social.',
                    'B' => 'Phishing não é uma técnica para testar segurança, mas sim um tipo de ataque malicioso.',
                    'D' => 'Phishing não é um software antivírus, mas sim uma ameaça contra a qual os antivírus tentam proteger.',
                    'default' => 'Phishing é uma técnica de ataque que usa engenharia social para obter informações confidenciais.'
                ]
            ],
            [
                'question' => 'Qual a importância da autenticação de dois fatores?',
                'explanation_correct' => 'A autenticação de dois fatores (2FA) adiciona uma camada extra de segurança além da senha, exigindo um segundo fator de verificação (como um código enviado ao celular), o que dificulta significativamente o acesso não autorizado mesmo se a senha for comprometida.',
                'explanation_incorrect' => [
                    'A' => 'A 2FA não aumenta a velocidade de login; na verdade, adiciona um passo extra que pode tornar o processo um pouco mais demorado, mas muito mais seguro.',
                    'C' => 'A 2FA não está relacionada ao consumo de banda de internet.',
                    'D' => 'A 2FA não permite compartilhar senhas com segurança; pelo contrário, é uma medida para proteger contas individuais.',
                    'default' => 'A autenticação de dois fatores adiciona uma camada extra de segurança além da senha.'
                ]
            ],
            [
                'question' => 'Como criar senhas seguras?',
                'explanation_correct' => 'Senhas seguras combinam letras maiúsculas, minúsculas, números e símbolos, têm pelo menos 12 caracteres, não usam informações pessoais ou palavras do dicionário, e são únicas para cada serviço.',
                'explanation_incorrect' => [
                    'A' => 'Usar palavras do dicionário torna as senhas vulneráveis a ataques de dicionário, onde hackers testam palavras comuns.',
                    'B' => 'Reutilizar senhas é extremamente perigoso, pois se uma conta for comprometida, todas as outras que usam a mesma senha também estarão em risco.',
                    'D' => 'Informações pessoais como datas de nascimento são facilmente descobertas por atacantes e não devem ser usadas em senhas.',
                    'default' => 'Senhas seguras devem combinar diferentes tipos de caracteres e ser únicas para cada serviço.'
                ]
            ],
            
            // Continuar com as demais questões...
            // 3. Cultura Organizacional HCP
            [
                'question' => 'Qual é a missão da HCP?',
                'explanation_correct' => 'A missão da HCP é proporcionar soluções inovadoras que transformam negócios e valorizam pessoas, buscando o equilíbrio entre resultados empresariais e o desenvolvimento humano.',
                'explanation_incorrect' => [
                    'A' => 'Maximizar lucros a qualquer custo não reflete os valores da HCP, que prioriza soluções éticas e sustentáveis.',
                    'C' => 'Ser a maior empresa do setor não é a missão principal, mas sim oferecer as melhores soluções com foco em pessoas.',
                    'D' => 'A expansão internacional pode ser um objetivo estratégico, mas não define a missão central da empresa.',
                    'default' => 'A missão da HCP foca em soluções inovadoras que transformam negócios enquanto valorizam as pessoas.'
                ]
            ],
            
            // 6. JavaScript Avançado
            [
                'question' => 'O que são closures em JavaScript?',
                'explanation_correct' => 'Closures são funções que têm acesso ao escopo pai mesmo após o escopo pai ter sido fechado. Isso permite que variáveis do escopo externo sejam "lembradas" e acessadas por funções internas, mesmo quando executadas fora de seu contexto original.',
                'explanation_incorrect' => [
                    'B' => 'Closures não estão relacionadas ao fechamento de conexões com banco de dados, mas sim ao acesso a variáveis de escopos externos.',
                    'C' => 'Closures não são técnicas para encerrar processos, mas sim um conceito de escopo em JavaScript.',
                    'D' => 'Closures não são um tipo de loop, mas sim funções com acesso ao escopo pai.',
                    'default' => 'Closures são funções que mantêm acesso às variáveis do escopo onde foram criadas, mesmo após esse escopo ter sido fechado.'
                ]
            ],
            [
                'question' => 'Explique a diferença entre Promise e async/await',
                'explanation_correct' => 'Promise é um objeto que representa uma operação assíncrona, enquanto async/await é uma sintaxe mais moderna que facilita o trabalho com Promises. Async/await torna o código assíncrono mais legível, permitindo escrevê-lo de forma similar ao código síncrono, mas por baixo dos panos ainda utiliza Promises.',
                'explanation_incorrect' => [
                    'A' => 'Promise e async/await não são a mesma coisa com sintaxes diferentes. Async/await é construído sobre Promises, oferecendo uma sintaxe mais limpa.',
                    'B' => 'Promise não é síncrona; ambos Promise e async/await lidam com operações assíncronas.',
                    'D' => 'Async/await funciona tanto no frontend quanto no backend, desde que o ambiente suporte JavaScript moderno.',
                    'default' => 'Promise é um objeto para operações assíncronas, enquanto async/await é uma sintaxe que facilita o trabalho com Promises.'
                ]
            ]
        ];

        // Inserir explicações nas questões
        foreach ($explanations as $explanationData) {
            $question = QuizQuestion::where('question', 'like', '%' . $explanationData['question'] . '%')->first();
            
            if ($question) {
                $question->update([
                    'explanation_correct' => $explanationData['explanation_correct'],
                    'explanation_incorrect' => $explanationData['explanation_incorrect'] ?? null,
                    'feedback_type' => 'immediate'
                ]);
                
                $this->command->info("Adicionada explicação para: {$explanationData['question']}");
            } else {
                $this->command->warn("Questão não encontrada: {$explanationData['question']}");
            }
        }
        
        // Atualizar questões sem explicações específicas
        QuizQuestion::whereNull('explanation_correct')->update([
            'explanation_correct' => 'Esta é a resposta correta. Parabéns por acertar!',
            'feedback_type' => 'immediate'
        ]);
        
        $this->command->info('Todas as explicações de questões foram atualizadas com sucesso!');
    }
}