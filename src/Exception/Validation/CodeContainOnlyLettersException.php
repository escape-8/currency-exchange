<?php

namespace App\Exception\Validation;

use RuntimeException;

class CodeContainOnlyLettersException extends RuntimeException
{
    protected $code = 409;
    protected $message = 'The currency code must contain only uppercase letters. Standard ISO 4217.';
}