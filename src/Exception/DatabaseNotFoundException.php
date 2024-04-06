<?php

namespace App\Exception;

use RuntimeException;

class DatabaseNotFoundException extends RuntimeException
{
    protected $code = 404;
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