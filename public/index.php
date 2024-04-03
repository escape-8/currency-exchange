<?php

declare(strict_types=1);

use App\DataGateway\CurrenciesDataGateway;
use App\DataGateway\ExchangeRatesDataGateway;
use App\DTO\ErrorResponseDTO;
use App\Exception\DatabaseNotFoundException;
use App\Exception\Validation\ContainsSpaceException;
use App\Exception\Validation\IncorrectInputException;
use App\Exception\Validation\NotContainsOnlyLettersException;
use App\Exception\Validation\CodeExistsException;
use App\Exception\Validation\InputDataLengthException;
use App\Exception\Validation\EmptyFieldException;
use App\Service\CurrenciesService;
use App\Service\CurrencyValidatorService;
use App\Service\ExchangeRatesService;
use App\Service\ExchangeRateValidatorService;
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
    $payload = json_encode($currenciesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

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
        $payload = json_encode($currencyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } catch (DatabaseNotFoundException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        $payload = json_encode($errorDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withStatus($e->getCode())->withHeader('Content-Type', 'application/json');
    }

});

$app->post('/currencies', function (Request $request, Response $response) use ($dataBase) {
    try {
        $currenciesData = new CurrenciesDataGateway($dataBase);
        $currenciesService = new CurrenciesService($currenciesData);
        $currencyValidation = new CurrencyValidatorService($currenciesData);
        $requestData = $request->getParsedBody();
        $currencyAddData = $currenciesService->addCurrency($currencyValidation->validate($requestData));
        $payload = json_encode($currencyAddData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } catch (EmptyFieldException|InputDataLengthException|CodeExistsException|NotContainsOnlyLettersException|ContainsSpaceException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        $payload = json_encode($errorDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withStatus($e->getCode())->withHeader('Content-Type', 'application/json');
    }
});

$app->get('/exchangeRates', function (Request $request, Response $response) use ($dataBase) {
    $currenciesData = new ExchangeRatesDataGateway($dataBase);
    $currenciesService = new ExchangeRatesService($currenciesData);
    $currenciesData = $currenciesService->getAllExchangeRates();
    $payload = json_encode($currenciesData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/exchangeRate[/{currencyPair}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    if (!array_key_exists('currencyPair', $args)) {
        $errorDTO = new ErrorResponseDTO('The currency code pair is missing in the URL address');
        $payload = json_encode($errorDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($payload);
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    try {
        $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
        $exchangeRateService = new ExchangeRatesService($exchangeRateData);
        $exchangeRateData = $exchangeRateService->getExchangeRate($args['currencyPair']);
        $payload = json_encode($exchangeRateData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    } catch (DatabaseNotFoundException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        $payload = json_encode($errorDTO, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withStatus($e->getCode())->withHeader('Content-Type', 'application/json');
    }

});

$app->run();