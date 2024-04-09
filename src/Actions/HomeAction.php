<?php

declare(strict_types=1);

namespace App\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeAction
{
    public function index(Request $request, Response $response): Response
    {
        $response->getBody()->write("Hello world!");
        return $response;
    }
}