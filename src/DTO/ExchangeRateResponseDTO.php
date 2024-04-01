<?php

namespace App\DTO;

class ExchangeRateResponseDTO implements DTOInterface
{
    public readonly int $id;
    public readonly CurrencyResponseDTO $baseCurrency;
    public readonly CurrencyResponseDTO $targetCurrency;
    public readonly float $rate;

    /**
     * @param int $id
     * @param CurrencyResponseDTO $baseCurrency
     * @param CurrencyResponseDTO $targetCurrency
     * @param float $rate
     */
    public function __construct(int $id, CurrencyResponseDTO $baseCurrency, CurrencyResponseDTO $targetCurrency, float $rate)
    {
        $this->id = $id;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->rate = $rate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'baseCurrency' => $this->baseCurrency,
            'targetCurrency' => $this->targetCurrency,
            'rate' => $this->rate
        ];
    }
}
