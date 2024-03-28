<?php

declare(strict_types=1);

use Middleware\TrailingSlashMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new TrailingSlashMiddleware());

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/currencies', function (Request $request, Response $response) use ($dataBase) {
    $currenciesData = new CurrenciesDataGateway($dataBase);
    $currenciesService = new CurrenciesService($currenciesData);
    $currenciesData = $currenciesService->getAllCurrencies();
    $currenciesDTO = $currenciesService->getCurrenciesDTO($currenciesData);
    $payload = json_encode($currenciesDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();