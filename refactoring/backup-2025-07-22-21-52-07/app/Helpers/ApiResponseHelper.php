<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;

if (!function_exists('apiResponse')) {
    /**
     * Retorna uma resposta JSON padronizada para APIs.
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @param array $errors
     * @param int $status
     * @return JsonResponse
     */
    function apiResponse(
        bool $success,
        string $message,
        $data = null,
        array $errors = [],
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
        ], $status);
    }
} 