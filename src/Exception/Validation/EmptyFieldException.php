<?php

namespace App\Exception\Validation;

use RuntimeException;

class EmptyFieldException extends RuntimeException
{
    protected $code = 400;
    protected $message = 'A required form field is missing';

    public function __construct(array $errors)
    {
        parent::__construct($this->message . ' : ' . implode(', ', $errors));
    }

}