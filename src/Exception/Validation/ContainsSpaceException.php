<?php

namespace App\Exception\Validation;

use RuntimeException;

class ContainsSpaceException extends RuntimeException
{
    protected $code = 409;

    public function __construct(string $validateFailString)
    {
        parent::__construct("The line '$validateFailString' must not contain spaces");
    }
}