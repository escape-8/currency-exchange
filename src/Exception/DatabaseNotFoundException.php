<?php

namespace App\Exception;

use Exception;

class DatabaseNotFoundException extends Exception
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