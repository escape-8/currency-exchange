<?php

namespace App\DTO;

class CurrencyDTO
{
    public readonly int $id;
    public readonly string $code;
    public readonly string $name;
    public readonly string $sign;

    /**
     * @param int $id
     * @param string $code
     * @param string $name
     * @param string $sign
     */
    public function __construct(int $id, string $code, string $name, string $sign)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->sign = $sign;
    }


}