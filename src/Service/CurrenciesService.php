<?php

namespace App\Service;

use App\DataGateway\CurrenciesDataGateway;
use App\Exception\CurrencyNotFoundException;

class CurrenciesService
{
    private CurrenciesDataGateway $dataGateway;

    /**
     * @param CurrenciesDataGateway $dataGateway
     */
    public function __construct(CurrenciesDataGateway $dataGateway)
    {
        $this->dataGateway = $dataGateway;
    }

    /**
     * @throws CurrencyNotFoundException
     */
    public function getCurrency(string $currencyCode): array
    {
        return $this->dataGateway->getCurrency($currencyCode);
    }

    public function getAllCurrencies(): array
    {
        return $this->dataGateway->getAllCurrencies();
    }

}