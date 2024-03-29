<?php

namespace App\Service;

use App\DataGateway\CurrenciesDataGateway;
use App\DTO\CurrencyDTO;
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

    /**
     * @param array $currenciesDbData
     * @return array<CurrencyDTO>
     */
    public function getCurrenciesDTO(array $currenciesDbData): array
    {
        $result = [];
        foreach ($currenciesDbData as $dbCurrency) {
            $result[] = $this->getCurrencyDTO($dbCurrency);
        }

        return $result;
    }

    /**
     * @param array $currencyDbData
     * @return CurrencyDTO
     */
    public function getCurrencyDTO(array $currencyDbData): CurrencyDTO
    {
        return new CurrencyDTO(
            $currencyDbData['id'],
            $currencyDbData['code'],
            $currencyDbData['full_name'],
            $currencyDbData['sign'],
        );
    }
}