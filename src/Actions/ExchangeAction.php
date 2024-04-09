<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\JsonResponse;
use App\Service\ExchangeService;
use App\Service\CurrencyExchangeValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExchangeAction
{
    private ExchangeService $exchangeService;
    private CurrencyExchangeValidatorService $currencyExchangeValidatorService;

    /**
     * @param ExchangeService $exchangeService
     * @param CurrencyExchangeValidatorService $currencyExchangeValidatorService
     */
    public function __construct(ExchangeService $exchangeService, CurrencyExchangeValidatorService $currencyExchangeValidatorService)
    {
        $this->exchangeService = $exchangeService;
        $this->currencyExchangeValidatorService = $currencyExchangeValidatorService;
    }

    public function exchange(Request $request): Response
    {
        $requestDTO = $this->currencyExchangeValidatorService->validate($request->getQueryParams());
        $exchangeData = $this->exchangeService->currencyExchange($requestDTO);
        return new JsonResponse($exchangeData);
    }
}