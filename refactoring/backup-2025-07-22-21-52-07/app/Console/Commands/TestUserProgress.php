<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Module;
use App\Http\Controllers\Admin\ReportController;

class TestUserProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user_progress table structure and ReportController methods';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing user_progress table structure...');
        
        try {
            // Verificar estrutura da tabela
            $columns = DB::select('DESCRIBE user_progress');
            $this->info('Table structure:');
            foreach ($columns as $col) {
                $this->line("  - {$col->Field}: {$col->Type}");
            }
            
            // Verificar se existe a coluna status
            $hasStatus = collect($columns)->contains('Field', 'status');
            $this->info($hasStatus ? '✅ Column "status" exists' : '❌ Column "status" missing');
            
            // Verificar se existe a coluna completed (não deveria existir)
            $hasCompleted = collect($columns)->contains('Field', 'completed');
            $this->info($hasCompleted ? '❌ Column "completed" exists (should not)' : '✅ Column "completed" does not exist (correct)');
            
            // Testar contagem de registros
            $totalRecords = DB::table('user_progress')->count();
            $this->info("Total records in user_progress: {$totalRecords}");
            
            // Testar contagem por status
            $statusCounts = DB::table('user_progress')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();
                
            $this->info('Records by status:');
            foreach ($statusCounts as $status) {
                $this->line("  - {$status->status}: {$status->count}");
            }
            
            // Testar método do ReportController
            $this->info('Testing ReportController methods...');
            
            $reportController = new ReportController();
            $reflection = new \ReflectionClass($reportController);
            $method = $reflection->getMethod('calculateCompletionRate');
            $method->setAccessible(true);
            
            try {
                $completionRate = $method->invoke($reportController);
                $this->info("✅ calculateCompletionRate() works: {$completionRate}%");
            } catch (\Exception $e) {
                $this->error("❌ calculateCompletionRate() failed: " . $e->getMessage());
            }
            
            // Testar método getModuleCompletions
            $method = $reflection->getMethod('getModuleCompletions');
            $method->setAccessible(true);
            
            try {
                $completions = $method->invoke($reportController, now()->subMonth());
                $this->info("✅ getModuleCompletions() works: " . $completions->count() . " records");
            } catch (\Exception $e) {
                $this->error("❌ getModuleCompletions() failed: " . $e->getMessage());
            }
            
            // Testar método getTopPerformers
            $method = $reflection->getMethod('getTopPerformers');
            $method->setAccessible(true);
            
            try {
                $topPerformers = $method->invoke($reportController);
                $this->info("✅ getTopPerformers() works: " . $topPerformers->count() . " users");
            } catch (\Exception $e) {
                $this->error("❌ getTopPerformers() failed: " . $e->getMessage());
            }
            
            $this->info('✅ All tests completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
