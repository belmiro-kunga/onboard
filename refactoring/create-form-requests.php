<?php

/**
 * Script para criar Form Requests
 */

echo "📊 Criando Form Requests...\n";

$basePath = dirname(__DIR__);
$requestPath = $basePath . '/app/Http/Requests';

// UserRequest
$userRequestContent = '<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route(\'user\') ? $this->route(\'user\')->id : null;

        return [
            \'name\' => \'required|string|max:255\',
            \'email\' => [
                \'required\',
                \'email\',
                \'max:255\',
                Rule::unique(\'users\')->ignore($userId)
            ],
            \'password\' => $userId ? \'nullable|string|min:8\' : \'required|string|min:8\',
            \'role\' => [\'required\', Rule::in([\'admin\', \'user\', \'manager\'])],
            \'department\' => \'nullable|string|max:255\',
            \'position\' => \'nullable|string|max:255\',
            \'is_active\' => \'boolean\',
        ];
    }

    public function messages(): array
    {
        return [
            \'name.required\' => \'O nome é obrigatório.\',
            \'email.required\' => \'O email é obrigatório.\',
            \'email.email\' => \'O email deve ser válido.\',
            \'email.unique\' => \'Este email já está em uso.\',
            \'password.required\' => \'A senha é obrigatória.\',
            \'password.min\' => \'A senha deve ter pelo menos 8 caracteres.\',
            \'role.required\' => \'O papel do usuário é obrigatório.\',
        ];
    }
}
';

file_put_contents($requestPath . '/UserRequest.php', $userRequestContent);
echo "  ✅ UserRequest criado\n";

// ModuleRequest
$moduleRequestContent = '<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            \'title\' => \'required|string|max:255\',
            \'description\' => \'required|string\',
            \'content_type\' => [\'required\', Rule::in([\'video\', \'text\', \'quiz\', \'mixed\'])],
            \'duration_minutes\' => \'required|integer|min:1\',
            \'difficulty_level\' => [\'required\', Rule::in([\'beginner\', \'intermediate\', \'advanced\'])],
            \'is_active\' => \'boolean\',
            \'order_index\' => \'nullable|integer|min:0\',
            \'course_id\' => \'nullable|exists:courses,id\',
            \'thumbnail\' => \'nullable|image|max:2048\',
        ];
    }

    public function messages(): array
    {
        return [
            \'title.required\' => \'O título do módulo é obrigatório.\',
            \'description.required\' => \'A descrição do módulo é obrigatória.\',
            \'content_type.required\' => \'O tipo de conteúdo é obrigatório.\',
            \'duration_minutes.required\' => \'A duração é obrigatória.\',
            \'difficulty_level.required\' => \'O nível de dificuldade é obrigatório.\',
        ];
    }
}
';

file_put_contents($requestPath . '/ModuleRequest.php', $moduleRequestContent);
echo "  ✅ ModuleRequest criado\n";

// QuizRequest
$quizRequestContent = '<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            \'title\' => \'required|string|max:255\',
            \'description\' => \'required|string\',
            \'time_limit\' => \'nullable|integer|min:1\',
            \'max_attempts\' => \'nullable|integer|min:1\',
            \'passing_score\' => \'required|integer|min:0|max:100\',
            \'difficulty_level\' => [\'required\', Rule::in([\'beginner\', \'intermediate\', \'advanced\'])],
            \'is_active\' => \'boolean\',
            \'randomize_questions\' => \'boolean\',
            \'show_results_immediately\' => \'boolean\',
        ];
    }

    public function messages(): array
    {
        return [
            \'title.required\' => \'O título do quiz é obrigatório.\',
            \'description.required\' => \'A descrição do quiz é obrigatória.\',
            \'passing_score.required\' => \'A nota mínima é obrigatória.\',
            \'difficulty_level.required\' => \'O nível de dificuldade é obrigatório.\',
        ];
    }
}
';

file_put_contents($requestPath . '/QuizRequest.php', $quizRequestContent);
echo "  ✅ QuizRequest criado\n";

echo "✅ Form Requests criados com sucesso!\n";