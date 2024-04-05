<?php

namespace App\DTO;

class CurrencyExchangeResponseDTO implements DTOInterface
{
    public readonly CurrencyResponseDTO $baseCurrency;
    public readonly CurrencyResponseDTO $targetCurrency;
    public readonly float $rate;
    public readonly float $amount;
    public readonly float $convertedAmount;

    /**
     * @param CurrencyResponseDTO $baseCurrency
     * @param CurrencyResponseDTO $targetCurrency
     * @param float $rate
     * @param float $amount
     * @param float $convertedAmount
     */
    public function __construct(CurrencyResponseDTO $baseCurrency, CurrencyResponseDTO $targetCurrency, float $rate, float $amount, float $convertedAmount)
    {
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->rate = $rate;
        $this->amount = $amount;
        $this->convertedAmount = $convertedAmount;
    }

    public function toArray(): array
    {
        return [
            'baseCurrency' => $this->baseCurrency,
            'targetCurrency' => $this->targetCurrency,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'convertedAmount' => $this->convertedAmount
        ];
    }
}