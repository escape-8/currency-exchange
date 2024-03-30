<?php

namespace App\DTO;

class CurrencyRequestDTO implements DTOInterface
{
    public readonly string $code;
    public readonly string $name;
    public readonly string $sign;

    /**
     * @param string $code
     * @param string $name
     * @param string $sign
     */
    public function __construct(string $code, string $name, string $sign)
    {
        $this->code = $code;
        $this->name = $name;
        $this->sign = $sign;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'sign' => $this->sign
        ];
    }
}