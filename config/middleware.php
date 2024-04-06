<?php

declare(strict_types=1);

use App\Middleware;
use Slim\App;

return static function (App $app) {
    $app->add(new Middleware\PDOExceptionMiddleware());
    $app->add(new Middleware\URLCurrencyCodesMiddleware());
    $app->addBodyParsingMiddleware();
    $app->addRoutingMiddleware();
    $app->add(new Middleware\TrailingSlashMiddleware());
};