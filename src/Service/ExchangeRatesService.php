<?php

namespace App\Service;

use App\DataGateway\ExchangeRatesDataGateway;
use App\DTO\CurrencyResponseDTO;
use App\DTO\ExchangeRateResponseDTO;
use App\Exception\DatabaseNotFoundException;
use App\Model\Currency;

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
     * @throws DatabaseNotFoundException
     */
    public function getExchangeRate(string $currencyPair): ExchangeRateResponseDTO
    {
        [$baseCurrency, $targetCurrency] = str_split($currencyPair, Currency::COUNT_LETTERS_IN_CODE);
        $exchangeRateDbData = $this->dataGateway->getExchangeRate($baseCurrency, $targetCurrency);

        return new ExchangeRateResponseDTO(
            $exchangeRateDbData['id'],
            new CurrencyResponseDTO(
                $exchangeRateDbData['base_currency_id'],
                $exchangeRateDbData['base_currency_code'],
                $exchangeRateDbData['base_currency_name'],
                $exchangeRateDbData['base_currency_sign'],
            ),
            new CurrencyResponseDTO(
                $exchangeRateDbData['target_currency_id'],
                $exchangeRateDbData['target_currency_code'],
                $exchangeRateDbData['target_currency_name'],
                $exchangeRateDbData['target_currency_sign'],
            ),
            $exchangeRateDbData['rate']
        );
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