<?php

namespace App\Exception\Validation;

use RuntimeException;

class DataExistsException extends RuntimeException
{
    protected $code = 409;
    protected $message;

    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
        parent::__construct();
    }

}