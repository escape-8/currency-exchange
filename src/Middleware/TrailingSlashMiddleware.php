<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Psr7\Response;

class TrailingSlashMiddleware
{
    public function __invoke(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
            $uri = $uri->withPath($path);

            if ($request->getMethod() == 'GET') {
                $response = new Response();
                return $response
                    ->withStatus(301)
                    ->withHeader('Location', (string)$uri);
            } else {
                $request = $request->withUri($uri);
            }
        }

        return $handler->handle($request);
    }
}