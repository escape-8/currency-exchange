<?php

namespace App\DTO;

class CurrencyExchangeRequestDTO implements DTOInterface
{
    public readonly string $baseCurrency;
    public readonly string $targetCurrency;
    public readonly float $amount;

    /**
     * @param string $baseCurrency
     * @param string $targetCurrency
     * @param string $amount
     */
    public function __construct(string $baseCurrency, string $targetCurrency, string $amount)
    {
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->amount = (float) $amount;
    }

    public function toArray(): array
    {
        return [
            'baseCurrency' => $this->baseCurrency,
            'targetCurrency' => $this->targetCurrency,
            'amount' => $this->amount
        ];
    }
}