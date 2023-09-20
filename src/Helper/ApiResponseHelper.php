<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseHelper
{
    public static function success(array|null $data, int $code = 200): JsonResponse
    {
        return new JsonResponse([
            'data' => $data,
            'message' => null,
            'code' => $code,
        ], $code);
    }

    public static function error(string $message, int $code): JsonResponse
    {
        return new JsonResponse([
            'data' => null,
            'message' => $message,
            'code' => $code,
        ], $code);
    }
}
