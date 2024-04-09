<?php

declare(strict_types=1);

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();

(require __DIR__ . '/dependencies.php')($container);

AppFactory::setContainer($container);
$app = AppFactory::create();

(require __DIR__ . '/middleware.php')($app);

(require __DIR__ . '/routes.php')($app);

return $app;