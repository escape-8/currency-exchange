<?php

declare(strict_types=1);

namespace App\Http;

use GuzzleHttp\Psr7\Response;
use JsonException;

class JsonResponse extends Response
{
    /**
     * @throws JsonException
     */
    public function __construct($data, int $statusCode = 200)
    {
        parent::__construct(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        );
    }
}