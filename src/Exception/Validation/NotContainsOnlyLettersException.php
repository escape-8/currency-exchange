<?php

namespace App\Exception\Validation;

use RuntimeException;

class NotContainsOnlyLettersException extends RuntimeException
{
    protected $code = 409;

    public function __construct(string $validateFailString)
    {
        parent::__construct("The '$validateFailString' must contain only letters");
    }
}