<?php

namespace App\DTO;

class CurrencyResponseDTO implements DTOInterface
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'sign' => $this->sign
        ];
    }
}