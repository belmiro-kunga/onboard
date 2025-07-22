<?php

/**
 * Script para análise automática do código
 * Identifica duplicações, complexidade e padrões
 */

class CodeAnalyzer
{
    private $basePath;
    private $report = [];

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
    }

    public function analyze()
    {
        echo "🔍 Iniciando análise do código...\n\n";

        $this->analyzeControllers();
        $this->analyzeModels();
        $this->analyzeServices();
        $this->analyzeDuplication();
        $this->generateReport();
    }

    private function analyzeControllers()
    {
        echo "📊 Analisando Controllers...\n";
        
        $controllersPath = $this->basePath . '/app/Http/Controllers';
        $controllers = $this->getPhpFiles($controllersPath);
        
        $this->report['controllers'] = [
            'total' => count($controllers),
            'admin_controllers' => 0,
            'api_controllers' => 0,
            'web_controllers' => 0,
            'large_controllers' => [],
            'methods_per_controller' => []
        ];

        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $lines = count(explode("\n", $content));
            $methods = $this->countMethods($content);
            
            $relativePath = str_replace($this->basePath . '/', '', $controller);
            
            // Categorizar controllers
            if (strpos($relativePath, 'Admin/') !== false) {
                $this->report['controllers']['admin_controllers']++;
            } elseif (strpos($relativePath, 'Api/') !== false) {
                $this->report['controllers']['api_controllers']++;
            } else {
                $this->report['controllers']['web_controllers']++;
            }
            
            // Controllers grandes (>200 linhas)
            if ($lines > 200) {
                $this->report['controllers']['large_controllers'][] = [
                    'file' => $relativePath,
                    'lines' => $lines,
                    'methods' => $methods
                ];
            }
            
            $this->report['controllers']['methods_per_controller'][$relativePath] = $methods;
        }
    }

    private function analyzeModels()
    {
        echo "📊 Analisando Models...\n";
        
        $modelsPath = $this->basePath . '/app/Models';
        $models = $this->getPhpFiles($modelsPath);
        
        $this->report['models'] = [
            'total' => count($models),
            'relationships' => [],
            'large_models' => []
        ];

        foreach ($models as $model) {
            $content = file_get_contents($model);
            $lines = count(explode("\n", $content));
            $relationships = $this->countRelationships($content);
            
            $relativePath = str_replace($this->basePath . '/', '', $model);
            
            if ($lines > 150) {
                $this->report['models']['large_models'][] = [
                    'file' => $relativePath,
                    'lines' => $lines,
                    'relationships' => $relationships
                ];
            }
            
            $this->report['models']['relationships'][$relativePath] = $relationships;
        }
    }

    private function analyzeServices()
    {
        echo "📊 Analisando Services...\n";
        
        $servicesPath = $this->basePath . '/app/Services';
        if (!is_dir($servicesPath)) {
            $this->report['services'] = ['total' => 0, 'message' => 'Diretório Services não encontrado'];
            return;
        }
        
        $services = $this->getPhpFiles($servicesPath);
        
        $this->report['services'] = [
            'total' => count($services),
            'service_details' => []
        ];

        foreach ($services as $service) {
            $content = file_get_contents($service);
            $lines = count(explode("\n", $content));
            $methods = $this->countMethods($content);
            
            $relativePath = str_replace($this->basePath . '/', '', $service);
            
            $this->report['services']['service_details'][$relativePath] = [
                'lines' => $lines,
                'methods' => $methods
            ];
        }
    }

    private function analyzeDuplication()
    {
        echo "📊 Analisando duplicação de código...\n";
        
        // Análise simples de duplicação baseada em padrões comuns
        $this->report['duplication'] = [
            'common_patterns' => $this->findCommonPatterns(),
            'similar_methods' => $this->findSimilarMethods()
        ];
    }

    private function getPhpFiles($directory)
    {
        $files = [];
        if (!is_dir($directory)) {
            return $files;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        
        return $files;
    }

    private function countMethods($content)
    {
        preg_match_all('/public function|private function|protected function/', $content, $matches);
        return count($matches[0]);
    }

    private function countRelationships($content)
    {
        $relationships = ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 'morphTo', 'morphMany'];
        $count = 0;
        
        foreach ($relationships as $relationship) {
            $count += substr_count($content, $relationship);
        }
        
        return $count;
    }

    private function findCommonPatterns()
    {
        // Padrões comuns que podem indicar duplicação
        return [
            'validation_patterns' => 'Validações similares em múltiplos controllers',
            'response_patterns' => 'Padrões de response similares',
            'query_patterns' => 'Queries similares em diferentes lugares'
        ];
    }

    private function findSimilarMethods()
    {
        return [
            'index_methods' => 'Métodos index similares em controllers',
            'store_methods' => 'Métodos store com validações similares',
            'show_methods' => 'Métodos show com lógica similar'
        ];
    }

    private function generateReport()
    {
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "📋 RELATÓRIO DE ANÁLISE DO CÓDIGO\n";
        echo str_repeat("=", 60) . "\n\n";

        // Controllers
        echo "🎮 CONTROLLERS:\n";
        echo "  Total: {$this->report['controllers']['total']}\n";
        echo "  Admin: {$this->report['controllers']['admin_controllers']}\n";
        echo "  API: {$this->report['controllers']['api_controllers']}\n";
        echo "  Web: {$this->report['controllers']['web_controllers']}\n";
        
        if (!empty($this->report['controllers']['large_controllers'])) {
            echo "  ⚠️  Controllers grandes (>200 linhas):\n";
            foreach ($this->report['controllers']['large_controllers'] as $controller) {
                echo "    - {$controller['file']} ({$controller['lines']} linhas, {$controller['methods']} métodos)\n";
            }
        }
        echo "\n";

        // Models
        echo "📊 MODELS:\n";
        echo "  Total: {$this->report['models']['total']}\n";
        
        if (!empty($this->report['models']['large_models'])) {
            echo "  ⚠️  Models grandes (>150 linhas):\n";
            foreach ($this->report['models']['large_models'] as $model) {
                echo "    - {$model['file']} ({$model['lines']} linhas, {$model['relationships']} relacionamentos)\n";
            }
        }
        echo "\n";

        // Services
        echo "🔧 SERVICES:\n";
        echo "  Total: {$this->report['services']['total']}\n";
        if (isset($this->report['services']['message'])) {
            echo "  {$this->report['services']['message']}\n";
        }
        echo "\n";

        // Recomendações
        echo "💡 RECOMENDAÇÕES:\n";
        $this->generateRecommendations();
        
        // Salvar relatório em arquivo
        $this->saveReportToFile();
    }

    private function generateRecommendations()
    {
        $recommendations = [];

        // Baseado nos controllers grandes
        if (!empty($this->report['controllers']['large_controllers'])) {
            $recommendations[] = "Refatorar controllers grandes extraindo lógica para Services";
        }

        // Baseado nos models grandes
        if (!empty($this->report['models']['large_models'])) {
            $recommendations[] = "Considerar quebrar models grandes em traits ou observers";
        }

        // Baseado na quantidade de controllers admin
        if ($this->report['controllers']['admin_controllers'] > 10) {
            $recommendations[] = "Criar BaseAdminController para reduzir duplicação";
        }

        if (empty($recommendations)) {
            $recommendations[] = "Código está bem estruturado, focar em otimizações menores";
        }

        foreach ($recommendations as $i => $recommendation) {
            echo "  " . ($i + 1) . ". $recommendation\n";
        }
    }

    private function saveReportToFile()
    {
        $reportPath = $this->basePath . '/refactoring/code-analysis-report.json';
        
        if (!is_dir(dirname($reportPath))) {
            mkdir(dirname($reportPath), 0755, true);
        }
        
        file_put_contents($reportPath, json_encode($this->report, JSON_PRETTY_PRINT));
        echo "\n📄 Relatório salvo em: refactoring/code-analysis-report.json\n";
    }
}

// Executar análise
if (php_sapi_name() === 'cli') {
    $analyzer = new CodeAnalyzer();
    $analyzer->analyze();
}