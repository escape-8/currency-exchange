<?php

namespace App\Exception;

class CurrencyNotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = 'Currency not found';
}