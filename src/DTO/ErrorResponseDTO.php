<?php

namespace App\DTO;

class ErrorResponseDTO
{
    public readonly string $message;

    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}