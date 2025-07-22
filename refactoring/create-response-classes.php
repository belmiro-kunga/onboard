<?php

/**
 * Script para criar classes de Response padronizadas
 */

echo "📊 Criando Response classes...\n";

$basePath = dirname(__DIR__);
$responsePath = $basePath . '/app/Http/Responses';

if (!is_dir($responsePath)) {
    mkdir($responsePath, 0755, true);
}

// ApiResponse
$apiResponseContent = '<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, string $message = \'Success\', int $status = 200): JsonResponse
    {
        return response()->json([
            \'success\' => true,
            \'message\' => $message,
            \'data\' => $data
        ], $status);
    }

    public static function error(string $message = \'Error\', $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            \'success\' => false,
            \'message\' => $message,
            \'errors\' => $errors
        ], $status);
    }

    public static function validationError($errors, string $message = \'Validation failed\'): JsonResponse
    {
        return self::error($message, $errors, 422);
    }

    public static function notFound(string $message = \'Resource not found\'): JsonResponse
    {
        return self::error($message, null, 404);
    }

    public static function unauthorized(string $message = \'Unauthorized\'): JsonResponse
    {
        return self::error($message, null, 401);
    }

    public static function paginated($data, string $message = \'Success\'): JsonResponse
    {
        return response()->json([
            \'success\' => true,
            \'message\' => $message,
            \'data\' => $data->items(),
            \'pagination\' => [
                \'current_page\' => $data->currentPage(),
                \'last_page\' => $data->lastPage(),
                \'per_page\' => $data->perPage(),
                \'total\' => $data->total(),
                \'from\' => $data->firstItem(),
                \'to\' => $data->lastItem(),
            ]
        ]);
    }
}
';

file_put_contents($responsePath . '/ApiResponse.php', $apiResponseContent);
echo "  ✅ ApiResponse criado\n";

// WebResponse
$webResponseContent = '<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;

class WebResponse
{
    public static function success(string $route, string $message = \'Operação realizada com sucesso!\', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with(\'success\', $message);
    }

    public static function error(string $route, string $message = \'Ocorreu um erro!\', array $parameters = []): RedirectResponse
    {
        return redirect()->route($route, $parameters)->with(\'error\', $message);
    }

    public static function backSuccess(string $message = \'Operação realizada com sucesso!\'): RedirectResponse
    {
        return back()->with(\'success\', $message);
    }

    public static function backError(string $message = \'Ocorreu um erro!\'): RedirectResponse
    {
        return back()->with(\'error\', $message);
    }

    public static function backWithErrors($errors, string $message = \'Verifique os dados informados\'): RedirectResponse
    {
        return back()->withErrors($errors)->with(\'error\', $message)->withInput();
    }

    public static function redirectToLogin(string $message = \'Você precisa estar logado\'): RedirectResponse
    {
        return redirect()->route(\'login\')->with(\'error\', $message);
    }

    public static function backWarning(string $message): RedirectResponse
    {
        return back()->with(\'warning\', $message);
    }
}
';

file_put_contents($responsePath . '/WebResponse.php', $webResponseContent);
echo "  ✅ WebResponse criado\n";

echo "✅ Response classes criadas com sucesso!\n";