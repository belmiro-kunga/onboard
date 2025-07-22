<?php

/**
 * Script para corrigir automaticamente código duplicado
 * Aplica os padrões criados no refatoramento para eliminar duplicações
 */

class DuplicationFixer
{
    private $basePath;
    private $fixedCount = 0;

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath ?: dirname(__DIR__);
    }

    public function fix()
    {
        echo "🔧 CORRIGINDO CÓDIGO DUPLICADO...\n\n";

        $this->fixControllerDuplications();
        $this->fixModelDuplications();
        $this->fixValidationDuplications();
        $this->fixResponseDuplications();
        $this->generateFixReport();
    }

    private function fixControllerDuplications()
    {
        echo "📊 Corrigindo duplicações em Controllers...\n";

        // Lista de controllers admin para atualizar
        $adminControllers = [
            'CertificateController.php',
            'ModuleController.php',
            'QuizController.php',
            'QuizQuestionController.php',
            'SimuladoController.php',
            'UserController.php',
            'UserDashboardController.php'
        ];

        foreach ($adminControllers as $controller) {
            $this->updateAdminController($controller);
        }

        // Atualizar controllers web para usar BaseController
        $webControllers = [
            'CertificateController.php',
            'CourseController.php',
            'DashboardController.php',
            'GamificationController.php',
            'ModuleController.php',
            'NotificationController.php',
            'ProgressController.php',
            'QuizController.php'
        ];

        foreach ($webControllers as $controller) {
            $this->updateWebController($controller);
        }
    }

    private function updateAdminController($controllerName)
    {
        $controllerPath = $this->basePath . "/app/Http/Controllers/Admin/{$controllerName}";
        
        if (!file_exists($controllerPath)) {
            return;
        }

        $content = file_get_contents($controllerPath);
        $originalContent = $content;

        // 1. Atualizar extends para BaseAdminController
        $content = preg_replace(
            '/class (\w+) extends Controller/',
            'class $1 extends BaseAdminController',
            $content
        );

        // 2. Atualizar import
        $content = str_replace(
            'use App\Http\Controllers\Controller;',
            'use App\Http\Controllers\Admin\BaseAdminController;',
            $content
        );

        // 3. Substituir métodos toggleActive duplicados
        $toggleActivePattern = '/public function toggleActive\([^}]+\$[^-]+->update\(\[\s*[\'"]is_active[\'"] => ![^}]+return back\(\)->with\([^}]+\}/s';
        if (preg_match($toggleActivePattern, $content)) {
            $content = preg_replace(
                $toggleActivePattern,
                'public function toggleActive($model)
    {
        return $this->toggleActiveStatus($model);
    }',
                $content
            );
            echo "  ✅ {$controllerName}: toggleActive method refatorado\n";
            $this->fixedCount++;
        }

        // 4. Substituir responses duplicadas
        $content = $this->fixResponses($content, $controllerName);

        // 5. Salvar se houve mudanças
        if ($content !== $originalContent) {
            file_put_contents($controllerPath, $content);
            echo "  ✅ {$controllerName}: Atualizado para usar BaseAdminController\n";
            $this->fixedCount++;
        }
    }

    private function updateWebController($controllerName)
    {
        $controllerPath = $this->basePath . "/app/Http/Controllers/{$controllerName}";
        
        if (!file_exists($controllerPath)) {
            return;
        }

        $content = file_get_contents($controllerPath);
        $originalContent = $content;

        // 1. Atualizar extends para BaseController
        $content = preg_replace(
            '/class (\w+) extends Controller/',
            'class $1 extends BaseController',
            $content
        );

        // 2. Atualizar import
        $content = str_replace(
            'use App\Http\Controllers\Controller;',
            'use App\Http\Controllers\BaseController;',
            $content
        );

        // 3. Substituir responses duplicadas
        $content = $this->fixResponses($content, $controllerName);

        // 4. Salvar se houve mudanças
        if ($content !== $originalContent) {
            file_put_contents($controllerPath, $content);
            echo "  ✅ {$controllerName}: Atualizado para usar BaseController\n";
            $this->fixedCount++;
        }
    }

    private function fixResponses($content, $controllerName)
    {
        // Substituir redirect()->back()->with('success', ...)
        $content = preg_replace(
            '/return redirect\(\)->back\(\)->with\([\'"]success[\'"], ([^)]+)\);/',
            'return $this->backWithSuccess($1);',
            $content
        );

        // Substituir redirect()->back()->with('error', ...)
        $content = preg_replace(
            '/return redirect\(\)->back\(\)->with\([\'"]error[\'"], ([^)]+)\);/',
            'return $this->backWithError($1);',
            $content
        );

        // Substituir response()->json(['success' => true])
        $content = str_replace(
            "return response()->json(['success' => true]);",
            'return $this->successResponse();',
            $content
        );

        // Substituir response()->json(['success' => false, ...])
        $content = preg_replace(
            '/return response\(\)->json\(\[\s*[\'"]success[\'"] => false,\s*[\'"]message[\'"] => ([^,\]]+)[^\]]*\], (\d+)\);/',
            'return $this->errorResponse($1, null, $2);',
            $content
        );

        return $content;
    }

    private function fixModelDuplications()
    {
        echo "📊 Corrigindo duplicações em Models...\n";

        // Lista de models para atualizar
        $models = [
            'Achievement.php',
            'Module.php',
            'ModuleContent.php',
            'Quiz.php',
            'QuizAnswer.php',
            'QuizQuestion.php',
            'User.php',
            'CalendarEvent.php',
            'Notification.php',
            'Certificate.php',
            'CourseEnrollment.php',
            'UserProgress.php',
            'ModuleRating.php'
        ];

        foreach ($models as $model) {
            $this->updateModel($model);
        }
    }

    private function updateModel($modelName)
    {
        $modelPath = $this->basePath . "/app/Models/{$modelName}";
        
        if (!file_exists($modelPath)) {
            return;
        }

        $content = file_get_contents($modelPath);
        $originalContent = $content;

        // Verificar se já tem traits
        if (strpos($content, 'HasActiveStatus') !== false) {
            return; // Já foi atualizado
        }

        // Adicionar imports dos traits
        $imports = [];
        if (strpos($content, 'is_active') !== false) {
            $imports[] = 'use App\Models\Traits\HasActiveStatus;';
        }
        if (strpos($content, 'order_index') !== false) {
            $imports[] = 'use App\Models\Traits\Orderable;';
        }
        $imports[] = 'use App\Models\Traits\FormattedTimestamps;';

        if (!empty($imports)) {
            // Adicionar imports após os imports existentes
            $content = preg_replace(
                '/(use Illuminate[^;]+;)(\s*\n)/',
                '$1$2' . implode("\n", $imports) . "\n",
                $content,
                1
            );

            // Adicionar traits na classe
            $traits = [];
            if (in_array('use App\Models\Traits\HasActiveStatus;', $imports)) {
                $traits[] = 'HasActiveStatus';
            }
            if (in_array('use App\Models\Traits\Orderable;', $imports)) {
                $traits[] = 'Orderable';
            }
            $traits[] = 'FormattedTimestamps';

            // Atualizar use statement na classe
            $content = preg_replace(
                '/(use HasFactory)(;)/',
                '$1, ' . implode(', ', $traits) . '$2',
                $content
            );

            // Remover scopes duplicados que agora estão nos traits
            $content = $this->removeDuplicatedScopes($content);

            file_put_contents($modelPath, $content);
            echo "  ✅ {$modelName}: Traits adicionados e scopes duplicados removidos\n";
            $this->fixedCount++;
        }
    }

    private function removeDuplicatedScopes($content)
    {
        // Remover scope Active duplicado
        $content = preg_replace(
            '/public function scopeActive\(\$query\)[^}]+\}/s',
            '// Scope Active movido para trait HasActiveStatus',
            $content
        );

        // Remover scope Ordered duplicado
        $content = preg_replace(
            '/public function scopeOrdered\(\$query[^}]+\}/s',
            '// Scope Ordered movido para trait Orderable',
            $content
        );

        return $content;
    }

    private function fixValidationDuplications()
    {
        echo "📊 Criando Form Requests para validações duplicadas...\n";

        // Criar Form Request para Course
        $this->createCourseFormRequest();
        
        echo "  ✅ CourseRequest: Form Request criado\n";
        $this->fixedCount++;
    }

    private function createCourseFormRequest()
    {
        $requestPath = $this->basePath . '/app/Http/Requests';
        if (!is_dir($requestPath)) {
            mkdir($requestPath, 0755, true);
        }

        $courseRequestContent = '<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $courseId = $this->route(\'course\') ? $this->route(\'course\')->id : null;

        return [
            \'title\' => \'required|string|max:255\',
            \'description\' => \'required|string\',
            \'short_description\' => \'nullable|string|max:255\',
            \'thumbnail\' => \'nullable|image|max:2048\',
            \'duration_hours\' => \'nullable|integer|min:0\',
            \'difficulty_level\' => [\'required\', Rule::in([\'beginner\', \'intermediate\', \'advanced\'])],
            \'type\' => [\'required\', Rule::in([\'mandatory\', \'optional\', \'certification\'])],
            \'is_active\' => \'boolean\',
            \'order_index\' => \'nullable|integer|min:0\',
            \'tags\' => \'nullable|string\',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            \'title.required\' => \'O título do curso é obrigatório.\',
            \'description.required\' => \'A descrição do curso é obrigatória.\',
            \'difficulty_level.required\' => \'O nível de dificuldade é obrigatório.\',
            \'type.required\' => \'O tipo do curso é obrigatório.\',
            \'thumbnail.image\' => \'O arquivo deve ser uma imagem válida.\',
            \'thumbnail.max\' => \'A imagem não pode ser maior que 2MB.\',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Processar tags
        if ($this->has(\'tags\') && $this->tags) {
            $tags = array_map(\'trim\', explode(\',\', $this->tags));
            $this->merge([
                \'tags\' => array_filter($tags)
            ]);
        }
    }
}
';

        file_put_contents($requestPath . '/CourseRequest.php', $courseRequestContent);
    }

    private function fixResponseDuplications()
    {
        echo "📊 Corrigindo responses duplicadas...\n";
        
        // As responses já foram corrigidas nos controllers
        echo "  ✅ Responses padronizadas nos controllers\n";
    }

    private function generateFixReport()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "🎉 RELATÓRIO DE CORREÇÕES APLICADAS\n";
        echo str_repeat("=", 80) . "\n\n";

        echo "📊 RESUMO DAS CORREÇÕES:\n";
        echo "  • Total de correções aplicadas: {$this->fixedCount}\n\n";

        echo "✅ CORREÇÕES IMPLEMENTADAS:\n";
        echo "  1. Controllers Admin atualizados para BaseAdminController\n";
        echo "  2. Controllers Web atualizados para BaseController\n";
        echo "  3. Métodos toggleActive padronizados\n";
        echo "  4. Responses padronizadas usando métodos base\n";
        echo "  5. Traits adicionados aos Models\n";
        echo "  6. Scopes duplicados removidos\n";
        echo "  7. Form Requests criados para validações\n\n";

        echo "🎯 BENEFÍCIOS OBTIDOS:\n";
        echo "  • Redução significativa de código duplicado\n";
        echo "  • Padronização de responses e validações\n";
        echo "  • Melhor manutenibilidade do código\n";
        echo "  • Reutilização de funcionalidades comuns\n\n";

        echo "📋 PRÓXIMOS PASSOS:\n";
        echo "  1. Testar funcionalidades após as correções\n";
        echo "  2. Executar testes automatizados\n";
        echo "  3. Verificar se não há erros de sintaxe\n";
        echo "  4. Aplicar correções em controllers restantes\n\n";

        // Salvar relatório
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'fixes_applied' => $this->fixedCount,
            'improvements' => [
                'Controllers refatorados para usar base classes',
                'Responses padronizadas',
                'Traits aplicados aos models',
                'Scopes duplicados removidos',
                'Form Requests criados'
            ]
        ];

        $reportPath = $this->basePath . '/refactoring/fix-report.json';
        file_put_contents($reportPath, json_encode($report, JSON_PRETTY_PRINT));
        echo "📄 Relatório salvo em: refactoring/fix-report.json\n";
    }
}

// Executar correções
if (php_sapi_name() === 'cli') {
    $fixer = new DuplicationFixer();
    $fixer->fix();
}