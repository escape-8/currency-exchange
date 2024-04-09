<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\JsonResponse;
use App\Service\CurrenciesService;
use App\Service\CurrencyValidatorService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CurrenciesAction
{
    private CurrenciesService  $currenciesService;
    private CurrencyValidatorService $currencyValidatorService;

    /**
     * @param CurrenciesService $currenciesService
     * @param CurrencyValidatorService $currencyValidatorService
     */
    public function __construct(CurrenciesService $currenciesService, CurrencyValidatorService $currencyValidatorService)
    {
        $this->currenciesService = $currenciesService;
        $this->currencyValidatorService = $currencyValidatorService;
    }

    public function show(): Response
    {
        return new JsonResponse($this->currenciesService->getAllCurrencies());
    }

    public function create(Request $request): Response
    {
        $requestData = $request->getParsedBody();
        $currencyDTO = $this->currencyValidatorService->validate($requestData);
        return new JsonResponse($this->currenciesService->addCurrency($currencyDTO) , 201);
    }
}