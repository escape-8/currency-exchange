<?php

namespace App\Model;

class Currency
{
    public const COUNT_LETTERS_IN_CODE = 3;
    private int $id;
    private string $code;
    private string $fullName;
    private string $sign;

    /**
     * @param int $id
     * @param string $code
     * @param string $fullName
     * @param string $sign
     */
    public function __construct(int $id, string $code, string $fullName, string $sign)
    {
        $this->id = $id;
        $this->code = $code;
        $this->fullName = $fullName;
        $this->sign = $sign;
    }

}