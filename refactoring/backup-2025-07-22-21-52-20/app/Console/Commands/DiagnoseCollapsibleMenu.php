<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DiagnoseCollapsibleMenu extends Command
{
    protected $signature = 'diagnose:collapsible-menu';
    protected $description = 'Diagnosticar problemas do menu colapsável';

    public function handle()
    {
        $this->info("🔍 Diagnosticando Menu Colapsável");
        $this->newLine();

        // Verificar se o layout existe
        $layoutPath = resource_path('views/components/layouts/app.blade.php');
        if (!file_exists($layoutPath)) {
            $this->error("❌ Layout não encontrado: {$layoutPath}");
            return 1;
        }

        $this->info("✅ Layout encontrado");

        // Verificar se o Alpine.js está sendo usado
        $layoutContent = file_get_contents($layoutPath);
        
        $checks = [
            'x-data' => 'Alpine.js x-data',
            'sidebarCollapsed' => 'Variável sidebarCollapsed',
            'localStorage' => 'localStorage',
            'transition-all' => 'Classes de transição',
            'w-64' => 'Largura expandida',
            'w-16' => 'Largura colapsada',
            'lg:pl-64' => 'Padding expandido',
            'lg:pl-16' => 'Padding colapsado',
            '@click' => 'Eventos de clique',
            'x-show' => 'Diretiva x-show',
            'x-text' => 'Diretiva x-text',
            'x-transition' => 'Transições Alpine',
        ];

        $this->info("🔧 Verificando elementos Alpine.js:");
        foreach ($checks as $element => $description) {
            if (str_contains($layoutContent, $element)) {
                $this->info("✅ {$description}: {$element}");
            } else {
                $this->warn("⚠️ {$description}: {$element} - NÃO ENCONTRADO");
            }
        }

        $this->newLine();

        // Verificar se há erros de sintaxe
        $this->info("🔍 Verificando sintaxe:");
        
        // Verificar se há aspas não fechadas
        $singleQuotes = substr_count($layoutContent, "'") % 2;
        $doubleQuotes = substr_count($layoutContent, '"') % 2;
        
        if ($singleQuotes !== 0) {
            $this->error("❌ Aspas simples não balanceadas");
        } else {
            $this->info("✅ Aspas simples balanceadas");
        }
        
        if ($doubleQuotes !== 0) {
            $this->error("❌ Aspas duplas não balanceadas");
        } else {
            $this->info("✅ Aspas duplas balanceadas");
        }

        // Verificar se há chaves não fechadas
        $braces = substr_count($layoutContent, '{') - substr_count($layoutContent, '}');
        if ($braces !== 0) {
            $this->error("❌ Chaves não balanceadas (diferença: {$braces})");
        } else {
            $this->info("✅ Chaves balanceadas");
        }

        $this->newLine();

        // Verificar se o botão toggle existe
        if (str_contains($layoutContent, 'sidebarCollapsed = !sidebarCollapsed')) {
            $this->info("✅ Botão toggle encontrado");
        } else {
            $this->error("❌ Botão toggle NÃO encontrado");
        }

        // Verificar se o localStorage está sendo usado
        if (str_contains($layoutContent, 'localStorage.getItem')) {
            $this->info("✅ localStorage configurado");
        } else {
            $this->warn("⚠️ localStorage NÃO configurado");
        }

        $this->newLine();

        // Verificar se há conflitos de CSS
        $this->info("🎨 Verificando classes CSS:");
        $cssClasses = [
            'fixed' => 'Posicionamento fixo',
            'inset-y-0' => 'Posicionamento vertical',
            'left-0' => 'Posicionamento à esquerda',
            'z-50' => 'Z-index alto',
            'bg-white' => 'Fundo branco',
            'shadow-lg' => 'Sombra',
            'transform' => 'Transformações',
            'transition-all' => 'Transições',
            'duration-300' => 'Duração da transição',
            'ease-in-out' => 'Easing da transição',
        ];

        foreach ($cssClasses as $class => $description) {
            if (str_contains($layoutContent, $class)) {
                $this->info("✅ {$description}: {$class}");
            } else {
                $this->warn("⚠️ {$description}: {$class} - NÃO ENCONTRADO");
            }
        }

        $this->newLine();

        $this->info("🌐 URLs para testar:");
        $this->line("• Página de teste: http://127.0.0.1:8000/test-collapsible");
        $this->line("• Dashboard: http://127.0.0.1:8000/dashboard");
        $this->line("• Simulados: http://127.0.0.1:8000/simulados");

        $this->newLine();

        $this->info("🔧 Comandos para resolver:");
        $this->line("1. Limpar cache: php artisan view:clear");
        $this->line("2. Compilar assets: npm run build");
        $this->line("3. Verificar console do navegador (F12)");
        $this->line("4. Verificar se Alpine.js está carregado: window.Alpine");

        $this->newLine();

        $this->info("📋 Checklist para verificar no navegador:");
        $this->line("1. Abra o DevTools (F12)");
        $this->line("2. Vá para a aba Console");
        $this->line("3. Digite: window.Alpine");
        $this->line("4. Se retornar undefined, Alpine.js não está carregado");
        $this->line("5. Verifique se há erros JavaScript");
        $this->line("6. Teste o botão de toggle");

        return 0;
    }
} 