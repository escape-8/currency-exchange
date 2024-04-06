<?php

namespace App\Middleware;

use PDOException;
use App\DTO\ErrorResponseDTO;
use App\Http\JsonResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class PDOExceptionMiddleware
{
    /**
     * @throws JsonException
     */
    public function __invoke(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (PDOException $e) {
            $message = 'Database error';
            if (str_starts_with($e->errorInfo[2], 'UNIQUE')) {
                $data = $request->getParsedBody();
                if ($data['code']) {
                    $message = 'A currency with this code already exists: ' . $data['code'];
                } elseif ($data['baseCurrencyCode'] && $data['targetCurrencyCode']) {
                    $message = 'A currency pair with this codes already exists: ' . $data['baseCurrencyCode'] . $data['targetCurrencyCode'];
                }
                return new JsonResponse(new ErrorResponseDTO($message), 409);
            }
            return new JsonResponse(new ErrorResponseDTO($message), 500);
        }
    }
}