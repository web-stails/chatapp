<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthException extends \Exception
{
    public function __construct(string $message = '', int $code = 422, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Отрисовка ошибки.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return \response()->json([
            'message' => $this->message,
        ], $this->code ?? 422);
    }
}
