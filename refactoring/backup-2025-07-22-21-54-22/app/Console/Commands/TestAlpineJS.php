<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestAlpineJS extends Command
{
    protected $signature = 'test:alpine';
    protected $description = 'Testar se o Alpine.js está funcionando corretamente';

    public function handle()
    {
        $this->info("🧪 Testando Alpine.js");
        $this->newLine();

        // Verificar se os assets estão compilados
        $viteManifestPath = public_path('build/manifest.json');
        if (!file_exists($viteManifestPath)) {
            $this->error("❌ Manifest do Vite não encontrado. Execute: npm run build");
            return 1;
        }

        $this->info("✅ Manifest do Vite encontrado");

        // Verificar se o Alpine.js está no manifest
        $manifest = json_decode(file_get_contents($viteManifestPath), true);
        $alpineFound = false;

        foreach ($manifest as $file => $info) {
            if (str_contains($file, 'app.js') || str_contains($file, 'alpine')) {
                $alpineFound = true;
                $this->info("✅ Alpine.js encontrado em: {$file}");
                break;
            }
        }

        if (!$alpineFound) {
            $this->warn("⚠️ Alpine.js pode não estar sendo carregado corretamente");
        }

        $this->newLine();

        $this->info("🔧 Verificando arquivos necessários:");

        // Verificar app.js
        $appJsPath = resource_path('js/app.js');
        if (file_exists($appJsPath)) {
            $appJsContent = file_get_contents($appJsPath);
            if (str_contains($appJsContent, 'Alpine')) {
                $this->info("✅ Alpine.js importado em app.js");
            } else {
                $this->error("❌ Alpine.js não encontrado em app.js");
            }
        } else {
            $this->error("❌ app.js não encontrado");
        }

        // Verificar se o layout está usando Alpine.js
        $layoutPath = resource_path('views/components/layouts/app.blade.php');
        if (file_exists($layoutPath)) {
            $layoutContent = file_get_contents($layoutPath);
            if (str_contains($layoutContent, 'x-data')) {
                $this->info("✅ Layout usando Alpine.js (x-data encontrado)");
            } else {
                $this->error("❌ Layout não está usando Alpine.js");
            }
        } else {
            $this->error("❌ Layout não encontrado");
        }

        $this->newLine();

        $this->info("📋 Comandos para resolver problemas:");
        $this->line("1. Compilar assets: npm run build");
        $this->line("2. Limpar cache: php artisan view:clear");
        $this->line("3. Limpar cache de configuração: php artisan config:clear");
        $this->line("4. Reiniciar servidor: php artisan serve");

        $this->newLine();

        $this->info("🌐 Para testar no navegador:");
        $this->line("1. Abra: http://127.0.0.1:8000/dashboard");
        $this->line("2. Abra o DevTools (F12)");
        $this->line("3. Vá para a aba Console");
        $this->line("4. Digite: window.Alpine");
        $this->line("5. Se retornar um objeto, Alpine.js está funcionando");

        return 0;
    }
} 