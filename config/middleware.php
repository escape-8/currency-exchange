<?php

declare(strict_types=1);

use App\Middleware;
use Slim\App;

return static function (App $app) {
    $app->add(new Middleware\PDOExceptionMiddleware());
    $app->add(new Middleware\DatabaseNotFoundExceptionHandler());
    $app->add(new Middleware\ValidationExceptionHandler());
    $app->add(new Middleware\URLCurrencyCodesMiddleware());
    $app->add(new Middleware\CORSResponseMiddleware());
    $app->add((new Middleware\ExceptionHandler()));
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->addErrorMiddleware(false,true,true);
    $app->add(new Middleware\TrailingSlashMiddleware());
};