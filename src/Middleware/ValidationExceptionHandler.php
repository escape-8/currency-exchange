<?php

namespace App\Middleware;

use App\DTO\ErrorResponseDTO;
use App\Exception\ValidationException;
use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionHandler
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ValidationException $e) {
            return new JsonResponse(new ErrorResponseDTO($e->getMessage()), $e->getCode());
        }
    }
}