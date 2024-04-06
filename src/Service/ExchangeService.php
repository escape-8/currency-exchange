<?php

namespace App\Service;

use App\DataGateway\ExchangeRatesDataGateway;
use App\DTO\CurrencyExchangeRequestDTO;
use App\DTO\CurrencyExchangeResponseDTO;
use App\Exception\DatabaseNotFoundException;

class ExchangeService
{
    private CurrenciesService $currenciesService;
    private ExchangeRatesDataGateway $exchangeRatesDataGateway;

    /**
     * @param CurrenciesService $currenciesService
     * @param ExchangeRatesDataGateway $exchangeRatesDataGateway
     */
    public function __construct(CurrenciesService $currenciesService, ExchangeRatesDataGateway $exchangeRatesDataGateway)
    {
        $this->currenciesService = $currenciesService;
        $this->exchangeRatesDataGateway = $exchangeRatesDataGateway;
    }

    public function currencyExchange(CurrencyExchangeRequestDTO $requestData): CurrencyExchangeResponseDTO
    {
        $currencyBase = $this->currenciesService->getCurrency($requestData->baseCurrency);
        $currencyTarget = $this->currenciesService->getCurrency($requestData->targetCurrency);
        $rate = $this->getExchangeRate($requestData->baseCurrency, $requestData->targetCurrency);
        $convertedAmount = (float) number_format($rate * $requestData->amount, 2, '.', '');

        return new CurrencyExchangeResponseDTO(
            $currencyBase,
            $currencyTarget,
            $rate,
            $requestData->amount,
            $convertedAmount
        );
    }

    public function getExchangeRate(string $baseCurrency, string $targetCurrency): float
    {
        if ($this->exchangeRatesDataGateway->isExchangeRateExists($baseCurrency, $targetCurrency)) {
            return $this->exchangeRatesDataGateway->getRateByCurrencyCodes($baseCurrency, $targetCurrency);
        } elseif ($this->exchangeRatesDataGateway->isExchangeRateExists($targetCurrency, $baseCurrency)) {
            $data = $this->exchangeRatesDataGateway->getRateByCurrencyCodes($targetCurrency, $baseCurrency);
            return $this->calcReverseCourse($data);
        } elseif (
            $this->exchangeRatesDataGateway->isExchangeRateExists('USD', $baseCurrency) &&
            $this->exchangeRatesDataGateway->isExchangeRateExists('USD', $targetCurrency)
        ) {
            $crossCourseData = [];
            $crossCourseData['baseRate'] = $this->exchangeRatesDataGateway->getRateByCurrencyCodes('USD', $baseCurrency);
            $crossCourseData['targetRate'] = $this->exchangeRatesDataGateway->getRateByCurrencyCodes('USD', $targetCurrency);
            return $this->calcCrossCourse($crossCourseData);
        }

        throw new DatabaseNotFoundException("Exchange rate $baseCurrency$targetCurrency not available");
    }

    /**
     * @param float $exchangeRate
     * @return float
     */
    protected function calcReverseCourse(float $exchangeRate): float
    {
        return (float) number_format(1 / $exchangeRate, 6, '.', '');
    }

    /**
     * @param array<float> $crossExchangeRates
     * @return float
     */
    protected function calcCrossCourse(array $crossExchangeRates): float
    {
        $baseCrossCourseRate = $crossExchangeRates['baseRate'];
        $targetCrossCourseRate = $crossExchangeRates['targetRate'];

        return (float) number_format($targetCrossCourseRate / $baseCrossCourseRate, 6, '.', '');
    }
}