<?php

namespace App\Exception;

use RuntimeException;
class ValidationException extends RuntimeException
{
    protected $code;
    protected $message;

    /**
     *  @param string $message
     *  @param int $statusCode
     */
    public function __construct(string $message, int $statusCode)
    {
        $this->message = $message;
        $this->code = $statusCode;
        parent::__construct();
    }
}