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
    public function getCurrency(string $currencyCode): CurrencyResponseDTO
    {
        $currencyDbData = $this->dataGateway->getCurrency($currencyCode);
        return new CurrencyResponseDTO(
            $currencyDbData['id'],
            $currencyDbData['code'],
            $currencyDbData['full_name'],
            $currencyDbData['sign'],
        );
    }

    /**
     * @return array<CurrencyResponseDTO>
     */
    public function getAllCurrencies(): array
    {
        $currenciesDbData = $this->dataGateway->getAllCurrencies();
        $result = [];
        foreach ($currenciesDbData as $dbCurrency) {
            $result[] = new CurrencyResponseDTO(
                $dbCurrency['id'],
                $dbCurrency['code'],
                $dbCurrency['full_name'],
                $dbCurrency['sign'],
            );
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