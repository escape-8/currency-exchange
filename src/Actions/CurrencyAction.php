<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\JsonResponse;
use App\Service\CurrenciesService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CurrencyAction
{
    private CurrenciesService $currenciesService;

    /**
     * @param CurrenciesService $currenciesService
     */
    public function __construct(CurrenciesService $currenciesService)
    {
        $this->currenciesService = $currenciesService;
    }

    public function show(Request $request): Response
    {
        return new JsonResponse($this->currenciesService->getCurrency($request->getAttribute('currency')));
    }
}