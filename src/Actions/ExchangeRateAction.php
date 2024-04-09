<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\JsonResponse;
use App\Service\ExchangeRatesService;
use App\Service\ExchangeRateValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExchangeRateAction
{
    private ExchangeRatesService $exchangeRatesService;
    private ExchangeRateValidatorService $exchangeRateValidatorService;

    /**
     * @param ExchangeRatesService $exchangeRatesService
     * @param ExchangeRateValidatorService $exchangeRateValidatorService
     */
    public function __construct(ExchangeRatesService $exchangeRatesService, ExchangeRateValidatorService $exchangeRateValidatorService)
    {
        $this->exchangeRatesService = $exchangeRatesService;
        $this->exchangeRateValidatorService = $exchangeRateValidatorService;
    }

    public function show(Request $request): Response
    {
        $currencyPair = $request->getAttribute('currencyPair');
        return new JsonResponse($this->exchangeRatesService->getExchangeRateByCurrencyPairCode($currencyPair));
    }

    public function update(Request $request): Response
    {
        $data = $request->getParsedBody();
        $currencyPair = $request->getAttribute('currencyPair');
        $this->exchangeRateValidatorService->validateRate($data);
        return new JsonResponse($this->exchangeRatesService->changeExchangeRate($currencyPair, $data));
    }
}