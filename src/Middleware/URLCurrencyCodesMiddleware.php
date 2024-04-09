<?php

namespace App\Middleware;

use App\DTO\ErrorResponseDTO;
use App\Http\JsonResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class URLCurrencyCodesMiddleware
{
    /**
     * @throws JsonException
     */
    public function __invoke(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $messagesForEmptyCurrencyCodes = [
            '/currency[/{currency}]' => 'The currency code is missing in the URL address',
            '/exchangeRate[/{currencyPair}]' => 'The currency code pair is missing in the URL address'
        ];

        $route = $request->getAttribute('__route__');
        $pattern = $route->getPattern();
        $arguments = $route->getArguments();

        if (isset($messagesForEmptyCurrencyCodes[$pattern]) && !$arguments) {
            return new JsonResponse(new ErrorResponseDTO($messagesForEmptyCurrencyCodes[$pattern]), 400);
        }

        return $handler->handle($request);
    }
}