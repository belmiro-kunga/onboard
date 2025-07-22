<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendModuleReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-module-reminders {--dry-run : Executar sem enviar notificações}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar lembretes automáticos para usuários com módulos pendentes';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $this->info('🚀 Iniciando envio de lembretes de módulos pendentes...');
        
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->warn('⚠️  Modo DRY RUN - Nenhuma notificação será enviada');
        }
        
        try {
            $startTime = microtime(true);
            
            if ($isDryRun) {
                // Simular contagem de usuários que receberiam lembretes
                $count = $this->getPendingUsersCount();
                $this->info("📊 {$count} usuários receberiam lembretes");
            } else {
                // Enviar lembretes reais
                $count = $notificationService->sendModuleReminders();
                $this->info("✅ {$count} lembretes enviados com sucesso");
            }
            
            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            $this->info("⏱️  Tempo de execução: {$executionTime}ms");
            
            // Log da execução
            Log::info('Comando de lembretes executado', [
                'count' => $count,
                'dry_run' => $isDryRun,
                'execution_time_ms' => $executionTime,
            ]);
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Erro ao enviar lembretes: {$e->getMessage()}");
            
            Log::error('Erro no comando de lembretes', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Obter contagem de usuários com módulos pendentes (para dry run).
     */
    private function getPendingUsersCount(): int
    {
        return \App\Models\User::where('is_active', true)
            ->whereHas('progress', function ($query) {
                $query->where('status', 'in_progress')
                      ->where('updated_at', '<=', now()->subDays(3));
            })
            ->count();
    }
} 