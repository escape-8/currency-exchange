<?php

namespace App\Service;

use App\DataGateway\ExchangeRatesDataGateway;
use App\DTO\CurrencyResponseDTO;
use App\DTO\ExchangeRateResponseDTO;

class ExchangeRatesService
{
    private ExchangeRatesDataGateway $dataGateway;

    /**
     * @param ExchangeRatesDataGateway $dataGateway
     */
    public function __construct(ExchangeRatesDataGateway $dataGateway)
    {
        $this->dataGateway = $dataGateway;
    }

    /**
     * @return array<ExchangeRateResponseDTO>
     */
    public function getAllExchangeRates(): array
    {
        $exchangeRatesDbData = $this->dataGateway->getAllCurrencies();
        $result = [];
        foreach ($exchangeRatesDbData as $dbCurrency) {
            $result[] = new ExchangeRateResponseDTO(
                $dbCurrency['id'],
                new CurrencyResponseDTO(
                    $dbCurrency['base_currency_id'],
                    $dbCurrency['base_currency_code'],
                    $dbCurrency['base_currency_name'],
                    $dbCurrency['base_currency_sign'],
                ),
                new CurrencyResponseDTO(
                    $dbCurrency['target_currency_id'],
                    $dbCurrency['target_currency_code'],
                    $dbCurrency['target_currency_name'],
                    $dbCurrency['target_currency_sign'],
                ),
                $dbCurrency['rate']
            );
        }

        return $result;
    }
}