<?php

namespace App\Model;

class Currency
{
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