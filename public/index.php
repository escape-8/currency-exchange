<?php

declare(strict_types=1);

use App\Exception\CurrencyNotFoundException;
use Middleware\TrailingSlashMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new TrailingSlashMiddleware());

$dbConnection = new DatabaseConnection();
$dataBase = $dbConnection->getConnection();

$app->get('/', function (Request $request, Response $response) {
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

$app->get('/currency[/{currency}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    if (!array_key_exists('currency', $args)) {
        $errorDTO = new ErrorResponseDTO('The currency code is missing in the URL address');
        $payload = json_encode($errorDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    try {
        $currenciesData = new CurrenciesDataGateway($dataBase);
        $currenciesService = new CurrenciesService($currenciesData);
        $currencyData = $currenciesService->getCurrency($args['currency']);
        $currencyDTO = $currenciesService->getCurrencyDTO($currencyData);
        $payload = json_encode($currencyDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } catch (CurrencyNotFoundException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        $payload = json_encode($errorDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withStatus($e->getCode())->withHeader('Content-Type', 'application/json');
    }

});

$app->run();