<?php

namespace App\Exception\Validation;

use RuntimeException;

class CodeExistsException extends RuntimeException
{
    protected $code = 409;
    protected $message;

    /**
     *
     * @param string $currencyCode
     * @param string $message
     */
    public function __construct(string $message, string $currencyCode)
    {
        $this->message = $message . ' : ' . $currencyCode;
        parent::__construct();
    }

}