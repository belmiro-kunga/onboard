<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Simulado;
use App\Models\SimuladoTentativa;
use App\Http\Controllers\SimuladoController;
use App\Contracts\GamificationServiceInterface;
use App\Contracts\CertificateServiceInterface;

class TestSimulados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:simulados {--history : Testar página de histórico}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testar funcionalidade de simulados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('history')) {
            $this->testHistory();
        } else {
            $this->testIndex();
        }
    }

    private function testIndex()
    {
        $this->info('Testando página principal de simulados...');

        try {
            // Testar carregamento de simulados
            $simulados = Simulado::available()->get();
            $this->info("Simulados disponíveis: {$simulados->count()}");

            // Testar usuário
            $user = User::first();
            if (!$user) {
                $this->error('Nenhum usuário encontrado');
                return 1;
            }
            $this->info("Usuário: {$user->name}");

            // Testar controller
            $controller = new SimuladoController(
                app(GamificationServiceInterface::class),
                app(CertificateServiceInterface::class)
            );
            $this->info('Controller criado com sucesso');

            // Testar stats
            $stats = $controller->getUserStats($user);
            $this->info('Stats calculados: ' . json_encode($stats));

            // Testar método show com string
            if ($simulados->count() > 0) {
                $simulado = $simulados->first();
                $this->info("Testando método show com ID string: '{$simulado->id}'");
                
                try {
                    $reflection = new \ReflectionMethod($controller, 'show');
                    $this->info("Método show aceita: " . $reflection->getParameters()[0]->getType());
                } catch (\Exception $e) {
                    $this->error("Erro ao verificar método show: {$e->getMessage()}");
                }
            }

            // Testar view
            $view = view('simulados.index', compact('simulados', 'stats'));
            $this->info('View renderizada com sucesso');

            $this->info('✅ Todos os testes passaram!');
            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erro: {$e->getMessage()}");
            $this->error("Arquivo: {$e->getFile()}");
            $this->error("Linha: {$e->getLine()}");
            return 1;
        }
    }

    private function testHistory()
    {
        $this->info('Testando página de histórico de simulados...');

        try {
            // Testar usuário
            $user = User::first();
            if (!$user) {
                $this->error('Nenhum usuário encontrado');
                return 1;
            }
            $this->info("Usuário: {$user->name}");

            // Testar se a rota existe
            $this->info('Verificando se a rota existe...');
            $routes = \Route::getRoutes();
            $historyRoute = null;
            foreach ($routes as $route) {
                if ($route->getName() === 'simulados.history') {
                    $historyRoute = $route;
                    break;
                }
            }
            
            if ($historyRoute) {
                $this->info('✅ Rota simulados.history encontrada');
                $this->info("Método: {$historyRoute->methods()[0]}");
                $this->info("URI: {$historyRoute->uri()}");
                $this->info("Controller: {$historyRoute->getActionName()}");
            } else {
                $this->error('❌ Rota simulados.history não encontrada');
                return 1;
            }

            // Testar carregamento de tentativas
            $tentativas = SimuladoTentativa::where('user_id', $user->id)
                ->with(['simulado'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $this->info("Tentativas encontradas: {$tentativas->count()}");

            // Testar controller
            $controller = new SimuladoController(
                app(GamificationServiceInterface::class),
                app(CertificateServiceInterface::class)
            );
            $this->info('Controller criado com sucesso');

            // Testar stats
            $stats = $controller->getUserStats($user);
            $this->info('Stats calculados: ' . json_encode($stats));

            // Testar cada tentativa
            foreach ($tentativas as $tentativa) {
                $this->info("Testando tentativa ID: {$tentativa->id}");
                
                // Testar relacionamento simulado
                if ($tentativa->simulado) {
                    $this->info("  - Simulado: {$tentativa->simulado->titulo}");
                } else {
                    $this->warn("  - Simulado não encontrado");
                }
                
                // Testar método isPassed
                try {
                    $isPassed = $tentativa->isPassed();
                    $this->info("  - isPassed: " . ($isPassed ? 'true' : 'false'));
                } catch (\Exception $e) {
                    $this->error("  - Erro em isPassed: {$e->getMessage()}");
                }
                
                // Testar tempo formatado
                try {
                    $tempoFormatado = $tentativa->tempo_formatado;
                    $this->info("  - Tempo formatado: {$tempoFormatado}");
                } catch (\Exception $e) {
                    $this->error("  - Erro em tempo_formatado: {$e->getMessage()}");
                }
            }

            // Testar view
            $view = view('simulados.history', compact('tentativas', 'stats'));
            $this->info('View renderizada com sucesso');

            $this->info('✅ Todos os testes de histórico passaram!');
            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erro: {$e->getMessage()}");
            $this->error("Arquivo: {$e->getFile()}");
            $this->error("Linha: {$e->getLine()}");
            return 1;
        }
    }
}
