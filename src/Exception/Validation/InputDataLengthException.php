<?php

namespace App\Exception\Validation;

use RuntimeException;

class InputDataLengthException extends RuntimeException
{
    protected $code = 409;
    protected $message;

    public function __construct(string $validateFailString, int $stringMaxLength)
    {
        parent::__construct("The '$validateFailString' must contain $stringMaxLength symbols");
    }


}