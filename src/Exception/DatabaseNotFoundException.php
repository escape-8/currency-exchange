<?php

namespace App\Exception;

use RuntimeException;

class DatabaseNotFoundException extends RuntimeException
{
    protected $code = 404;
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