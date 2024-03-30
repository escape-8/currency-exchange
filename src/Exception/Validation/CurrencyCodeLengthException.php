<?php

namespace App\Exception\Validation;

use RuntimeException;

class CurrencyCodeLengthException extends RuntimeException
{
    protected $code = 409;
    protected $message;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
        parent::__construct();
    }


}