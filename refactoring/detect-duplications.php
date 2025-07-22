<?php

/**
 * Script avançado para detectar código duplicado
 * Analisa padrões específicos, métodos similares e blocos de código repetidos
 */

class DuplicationDetector
{
    private $basePath;
    private $duplications = [];
    private $patterns = [];
    private $methods = [];

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
    }

    public function detect()
    {
        echo "🔍 DETECTANDO CÓDIGO DUPLICADO...\n\n";

        $this->detectControllerDuplications();
        $this->detectModelDuplications();
        $this->detectValidationDuplications();
        $this->detectQueryDuplications();
        $this->detectResponseDuplications();
        $this->generateDuplicationReport();
    }

    private function detectControllerDuplications()
    {
        echo "📊 Analisando duplicações em Controllers...\n";
        
        $controllers = $this->getPhpFiles($this->basePath . '/app/Http/Controllers');
        $methodPatterns = [];

        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $relativePath = str_replace($this->basePath . '/', '', $controller);
            
            // Detectar métodos index similares
            if (preg_match('/public function index\([^}]+\}/s', $content, $matches)) {
                $indexMethod = $this->normalizeCode($matches[0]);
                $methodPatterns['index'][$relativePath] = $indexMethod;
            }

            // Detectar métodos store similares
            if (preg_match('/public function store\([^}]+\}/s', $content, $matches)) {
                $storeMethod = $this->normalizeCode($matches[0]);
                $methodPatterns['store'][$relativePath] = $storeMethod;
            }

            // Detectar métodos destroy similares
            if (preg_match('/public function destroy\([^}]+\}/s', $content, $matches)) {
                $destroyMethod = $this->normalizeCode($matches[0]);
                $methodPatterns['destroy'][$relativePath] = $destroyMethod;
            }

            // Detectar métodos toggleActive
            if (preg_match('/public function toggleActive\([^}]+\}/s', $content, $matches)) {
                $toggleMethod = $this->normalizeCode($matches[0]);
                $methodPatterns['toggleActive'][$relativePath] = $toggleMethod;
            }
        }

        $this->findSimilarMethods($methodPatterns);
    }

    private function detectModelDuplications()
    {
        echo "📊 Analisando duplicações em Models...\n";
        
        $models = $this->getPhpFiles($this->basePath . '/app/Models');
        $scopePatterns = [];
        $relationshipPatterns = [];

        foreach ($models as $model) {
            $content = file_get_contents($model);
            $relativePath = str_replace($this->basePath . '/', '', $model);

            // Detectar scopes similares
            preg_match_all('/public function scope(\w+)\([^}]+\}/s', $content, $scopes);
            foreach ($scopes[0] as $i => $scope) {
                $scopeName = $scopes[1][$i];
                $normalizedScope = $this->normalizeCode($scope);
                $scopePatterns[$scopeName][$relativePath] = $normalizedScope;
            }

            // Detectar relacionamentos similares
            preg_match_all('/public function (\w+)\(\)[^}]+return \$this->(hasOne|hasMany|belongsTo|belongsToMany)\([^}]+\}/s', $content, $relationships);
            foreach ($relationships[0] as $i => $relationship) {
                $relationType = $relationships[2][$i];
                $normalizedRel = $this->normalizeCode($relationship);
                $relationshipPatterns[$relationType][$relativePath][] = $normalizedRel;
            }
        }

        $this->findSimilarScopes($scopePatterns);
        $this->findSimilarRelationships($relationshipPatterns);
    }

    private function detectValidationDuplications()
    {
        echo "📊 Analisando duplicações em Validações...\n";
        
        $controllers = $this->getPhpFiles($this->basePath . '/app/Http/Controllers');
        $validationPatterns = [];

        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $relativePath = str_replace($this->basePath . '/', '', $controller);

            // Detectar validações similares
            preg_match_all('/\$request->validate\(\s*\[([^\]]+)\]/s', $content, $validations);
            foreach ($validations[1] as $validation) {
                $normalizedValidation = $this->normalizeCode($validation);
                $validationPatterns[$normalizedValidation][] = $relativePath;
            }

            // Detectar validated() calls
            preg_match_all('/\$validated = \$request->validate\([^;]+;/s', $content, $validatedCalls);
            foreach ($validatedCalls[0] as $call) {
                $normalizedCall = $this->normalizeCode($call);
                $this->patterns['validation_calls'][$normalizedCall][] = $relativePath;
            }
        }

        $this->findDuplicatedValidations($validationPatterns);
    }

    private function detectQueryDuplications()
    {
        echo "📊 Analisando duplicações em Queries...\n";
        
        $files = array_merge(
            $this->getPhpFiles($this->basePath . '/app/Http/Controllers'),
            $this->getPhpFiles($this->basePath . '/app/Services')
        );

        $queryPatterns = [];

        foreach ($files as $file) {
            $content = file_get_contents($file);
            $relativePath = str_replace($this->basePath . '/', '', $file);

            // Detectar queries similares
            preg_match_all('/(\w+::where\([^;]+;|\w+::orderBy\([^;]+;|\w+::paginate\([^;]+;)/s', $content, $queries);
            foreach ($queries[0] as $query) {
                $normalizedQuery = $this->normalizeCode($query);
                $queryPatterns[$normalizedQuery][] = $relativePath;
            }

            // Detectar with() calls
            preg_match_all('/->with\(\[[^\]]+\]\)/s', $content, $withCalls);
            foreach ($withCalls[0] as $withCall) {
                $normalizedWith = $this->normalizeCode($withCall);
                $this->patterns['with_calls'][$normalizedWith][] = $relativePath;
            }
        }

        $this->findDuplicatedQueries($queryPatterns);
    }

    private function detectResponseDuplications()
    {
        echo "📊 Analisando duplicações em Responses...\n";
        
        $controllers = $this->getPhpFiles($this->basePath . '/app/Http/Controllers');
        $responsePatterns = [];

        foreach ($controllers as $controller) {
            $content = file_get_contents($controller);
            $relativePath = str_replace($this->basePath . '/', '', $controller);

            // Detectar redirect()->back()->with() patterns
            preg_match_all('/return redirect\(\)->back\(\)->with\([^;]+;/s', $content, $redirects);
            foreach ($redirects[0] as $redirect) {
                $normalizedRedirect = $this->normalizeCode($redirect);
                $responsePatterns['redirect_back'][$normalizedRedirect][] = $relativePath;
            }

            // Detectar response()->json() patterns
            preg_match_all('/return response\(\)->json\([^;]+;/s', $content, $jsonResponses);
            foreach ($jsonResponses[0] as $jsonResponse) {
                $normalizedJson = $this->normalizeCode($jsonResponse);
                $responsePatterns['json_response'][$normalizedJson][] = $relativePath;
            }

            // Detectar redirect()->route() patterns
            preg_match_all('/return redirect\(\)->route\([^;]+;/s', $content, $routeRedirects);
            foreach ($routeRedirects[0] as $routeRedirect) {
                $normalizedRoute = $this->normalizeCode($routeRedirect);
                $responsePatterns['redirect_route'][$normalizedRoute][] = $relativePath;
            }
        }

        $this->findDuplicatedResponses($responsePatterns);
    }

    private function normalizeCode($code)
    {
        // Remove espaços extras, quebras de linha e comentários
        $code = preg_replace('/\s+/', ' ', $code);
        $code = preg_replace('/\/\*.*?\*\//', '', $code);
        $code = preg_replace('/\/\/.*/', '', $code);
        return trim($code);
    }

    private function findSimilarMethods($methodPatterns)
    {
        foreach ($methodPatterns as $methodName => $methods) {
            $similarities = [];
            $methodFiles = array_keys($methods);
            
            for ($i = 0; $i < count($methodFiles); $i++) {
                for ($j = $i + 1; $j < count($methodFiles); $j++) {
                    $file1 = $methodFiles[$i];
                    $file2 = $methodFiles[$j];
                    $similarity = $this->calculateSimilarity($methods[$file1], $methods[$file2]);
                    
                    if ($similarity > 70) { // 70% de similaridade
                        $similarities[] = [
                            'files' => [$file1, $file2],
                            'similarity' => $similarity,
                            'method' => $methodName
                        ];
                    }
                }
            }
            
            if (!empty($similarities)) {
                $this->duplications['methods'][$methodName] = $similarities;
            }
        }
    }

    private function findSimilarScopes($scopePatterns)
    {
        foreach ($scopePatterns as $scopeName => $scopes) {
            if (count($scopes) > 1) {
                $this->duplications['scopes'][$scopeName] = array_keys($scopes);
            }
        }
    }

    private function findSimilarRelationships($relationshipPatterns)
    {
        foreach ($relationshipPatterns as $relationType => $relationships) {
            $duplicates = [];
            foreach ($relationships as $file => $rels) {
                if (count($rels) > 1) {
                    $duplicates[$file] = count($rels);
                }
            }
            if (!empty($duplicates)) {
                $this->duplications['relationships'][$relationType] = $duplicates;
            }
        }
    }

    private function findDuplicatedValidations($validationPatterns)
    {
        foreach ($validationPatterns as $validation => $files) {
            if (count($files) > 1) {
                $this->duplications['validations'][] = [
                    'pattern' => substr($validation, 0, 100) . '...',
                    'files' => $files,
                    'count' => count($files)
                ];
            }
        }
    }

    private function findDuplicatedQueries($queryPatterns)
    {
        foreach ($queryPatterns as $query => $files) {
            if (count($files) > 1) {
                $this->duplications['queries'][] = [
                    'pattern' => substr($query, 0, 100) . '...',
                    'files' => $files,
                    'count' => count($files)
                ];
            }
        }
    }

    private function findDuplicatedResponses($responsePatterns)
    {
        foreach ($responsePatterns as $type => $patterns) {
            foreach ($patterns as $pattern => $files) {
                if (count($files) > 1) {
                    $this->duplications['responses'][$type][] = [
                        'pattern' => substr($pattern, 0, 100) . '...',
                        'files' => $files,
                        'count' => count($files)
                    ];
                }
            }
        }
    }

    private function calculateSimilarity($str1, $str2)
    {
        similar_text($str1, $str2, $percent);
        return round($percent, 2);
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

    private function generateDuplicationReport()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "🔍 RELATÓRIO DE CÓDIGO DUPLICADO\n";
        echo str_repeat("=", 80) . "\n\n";

        $totalDuplications = 0;

        // Métodos duplicados
        if (isset($this->duplications['methods'])) {
            echo "🔄 MÉTODOS SIMILARES:\n";
            foreach ($this->duplications['methods'] as $methodName => $similarities) {
                echo "  📝 Método: {$methodName}\n";
                foreach ($similarities as $similarity) {
                    echo "    • {$similarity['similarity']}% similar entre:\n";
                    echo "      - {$similarity['files'][0]}\n";
                    echo "      - {$similarity['files'][1]}\n";
                    $totalDuplications++;
                }
            }
            echo "\n";
        }

        // Scopes duplicados
        if (isset($this->duplications['scopes'])) {
            echo "🔍 SCOPES DUPLICADOS:\n";
            foreach ($this->duplications['scopes'] as $scopeName => $files) {
                echo "  📝 Scope: {$scopeName}\n";
                echo "    Encontrado em " . count($files) . " arquivos:\n";
                foreach ($files as $file) {
                    echo "      - {$file}\n";
                }
                $totalDuplications++;
            }
            echo "\n";
        }

        // Validações duplicadas
        if (isset($this->duplications['validations'])) {
            echo "✅ VALIDAÇÕES DUPLICADAS:\n";
            foreach ($this->duplications['validations'] as $validation) {
                echo "  📝 Padrão: {$validation['pattern']}\n";
                echo "    Encontrado em {$validation['count']} arquivos:\n";
                foreach ($validation['files'] as $file) {
                    echo "      - {$file}\n";
                }
                $totalDuplications++;
            }
            echo "\n";
        }

        // Queries duplicadas
        if (isset($this->duplications['queries'])) {
            echo "🗄️ QUERIES DUPLICADAS:\n";
            foreach ($this->duplications['queries'] as $query) {
                echo "  📝 Padrão: {$query['pattern']}\n";
                echo "    Encontrado em {$query['count']} arquivos:\n";
                foreach ($query['files'] as $file) {
                    echo "      - {$file}\n";
                }
                $totalDuplications++;
            }
            echo "\n";
        }

        // Responses duplicadas
        if (isset($this->duplications['responses'])) {
            echo "📤 RESPONSES DUPLICADAS:\n";
            foreach ($this->duplications['responses'] as $type => $responses) {
                echo "  📝 Tipo: {$type}\n";
                foreach ($responses as $response) {
                    echo "    • Padrão: {$response['pattern']}\n";
                    echo "      Encontrado em {$response['count']} arquivos:\n";
                    foreach ($response['files'] as $file) {
                        echo "        - {$file}\n";
                    }
                    $totalDuplications++;
                }
            }
            echo "\n";
        }

        // Resumo
        echo "📊 RESUMO:\n";
        echo "  • Total de duplicações encontradas: {$totalDuplications}\n";
        
        if ($totalDuplications > 0) {
            echo "  ⚠️  RECOMENDAÇÕES:\n";
            echo "    1. Extrair métodos similares para BaseController\n";
            echo "    2. Criar traits para scopes duplicados\n";
            echo "    3. Centralizar validações em Form Requests\n";
            echo "    4. Criar Repository pattern para queries\n";
            echo "    5. Padronizar responses usando BaseController\n";
        } else {
            echo "  ✅ Parabéns! Nenhuma duplicação significativa encontrada!\n";
        }

        // Salvar relatório
        $reportPath = $this->basePath . '/refactoring/duplication-report.json';
        file_put_contents($reportPath, json_encode($this->duplications, JSON_PRETTY_PRINT));
        echo "\n📄 Relatório detalhado salvo em: refactoring/duplication-report.json\n";
    }
}

// Executar detecção
if (php_sapi_name() === 'cli') {
    $detector = new DuplicationDetector();
    $detector->detect();
}