<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\JsonResponse;
use App\Service\ExchangeRatesService;
use App\Service\ExchangeRateValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ExchangeRatesAction
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

    public function show(): Response
    {
        return new JsonResponse($this->exchangeRatesService->getAllExchangeRates());
    }

    public function create(Request $request): Response
    {
        $requestData = $request->getParsedBody();
        $exchangeRateDTO = $this->exchangeRateValidatorService->validate($requestData);
        return new JsonResponse($this->exchangeRatesService->addExchangeRate($exchangeRateDTO), 201);
    }
}