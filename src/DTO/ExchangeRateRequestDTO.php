<?php

namespace App\DTO;

class ExchangeRateRequestDTO implements DTOInterface
{
    public readonly int $baseCurrencyId;
    public readonly int $targetCurrencyId;
    public readonly float $rate;

    /**
     * @param int $baseCurrencyId
     * @param int $targetCurrencyId
     * @param string $rate
     */
    public function __construct(int $baseCurrencyId, int $targetCurrencyId, string $rate)
    {
        $this->baseCurrencyId = $baseCurrencyId;
        $this->targetCurrencyId = $targetCurrencyId;
        $this->rate = (float) $rate;
    }

    public function toArray(): array
    {
        return [
            'baseCurrencyId' => $this->baseCurrencyId,
            'targetCurrencyId' => $this->targetCurrencyId,
            'rate' => $this->rate
        ];
    }
}