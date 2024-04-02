<?php

namespace App\Model;

class ExchangeRate
{
    public const COUNT_LETTERS_IN_EXCHANGE_RATE_CODE = 6;
    private int $id;
    private Currency $baseCurrency;
    private Currency $targetCurrency;
    private float $rate;

    /**
     * @param int $id
     * @param Currency $baseCurrency
     * @param Currency $targetCurrency
     * @param float $rate
     */
    public function __construct(int $id, Currency $baseCurrency, Currency $targetCurrency, float $rate)
    {
        $this->id = $id;
        $this->baseCurrency = $baseCurrency;
        $this->targetCurrency = $targetCurrency;
        $this->rate = $rate;
    }


}