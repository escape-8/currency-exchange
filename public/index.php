<?php

declare(strict_types=1);

use App\DataGateway\CurrenciesDataGateway;
use App\DataGateway\ExchangeRatesDataGateway;
use App\DTO\ErrorResponseDTO;
use App\Exception\DatabaseNotFoundException;
use App\Exception\Validation\IncorrectInputException;
use App\Exception\Validation\DataExistsException;
use App\Exception\Validation\EmptyFieldException;
use App\Http\JsonResponse;
use App\Service\CurrenciesService;
use App\Service\CurrencyExchangeValidatorService;
use App\Service\CurrencyValidatorService;
use App\Service\ExchangeRatesService;
use App\Service\ExchangeRateValidatorService;
use App\Service\ExchangeService;
use Middleware\TrailingSlashMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$app->add(new TrailingSlashMiddleware());
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$dbConnection = new DatabaseConnection();
$dataBase = $dbConnection->getConnection();

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/currencies', function () use ($dataBase) {
    $currenciesData = new CurrenciesDataGateway($dataBase);
    $currenciesService = new CurrenciesService($currenciesData);
    $currenciesData = $currenciesService->getAllCurrencies();
    return new JsonResponse($currenciesData);
});

$app->get('/currency[/{currency}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    if (!array_key_exists('currency', $args)) {
        $errorDTO = new ErrorResponseDTO('The currency code is missing in the URL address');
        return new JsonResponse($errorDTO, 400);
    }

    try {
        $currenciesData = new CurrenciesDataGateway($dataBase);
        $currenciesService = new CurrenciesService($currenciesData);
        $currencyData = $currenciesService->getCurrency($args['currency']);
        return new JsonResponse($currencyData);
    } catch (DatabaseNotFoundException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        return new JsonResponse($errorDTO, $e->getCode());
    }

});

$app->post('/currencies', function (Request $request) use ($dataBase) {
    try {
        $currenciesData = new CurrenciesDataGateway($dataBase);
        $currenciesService = new CurrenciesService($currenciesData);
        $currencyValidation = new CurrencyValidatorService($currenciesData);
        $requestData = $request->getParsedBody();
        $currencyAddData = $currenciesService->addCurrency($currencyValidation->validate($requestData));
        return new JsonResponse($currencyAddData, 201);
    } catch (EmptyFieldException|DataExistsException|IncorrectInputException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        return new JsonResponse($errorDTO, $e->getCode());
    }
});

$app->get('/exchangeRates', function () use ($dataBase) {
    $currenciesData = new ExchangeRatesDataGateway($dataBase);
    $currenciesService = new ExchangeRatesService($currenciesData);
    $currenciesData = $currenciesService->getAllExchangeRates();
    return new JsonResponse($currenciesData);
});

$app->get('/exchangeRate[/{currencyPair}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    if (!array_key_exists('currencyPair', $args)) {
        $errorDTO = new ErrorResponseDTO('The currency code pair is missing in the URL address');
        return new JsonResponse($errorDTO, 400);
    }

    try {
        $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
        $exchangeRateService = new ExchangeRatesService($exchangeRateData);
        $exchangeRateData = $exchangeRateService->getExchangeRateByCurrencyPairCode($args['currencyPair']);
        return new JsonResponse($exchangeRateData);
    } catch (DatabaseNotFoundException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        return new JsonResponse($errorDTO, $e->getCode());
    }

});

$app->post('/exchangeRates', function (Request $request) use ($dataBase) {
    try {
        $currencyData = new CurrenciesDataGateway($dataBase);
        $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
        $exchangeRateService = new ExchangeRatesService($exchangeRateData);
        $exchangeRateValidation = new ExchangeRateValidatorService($exchangeRateData, $currencyData);
        $requestData = $request->getParsedBody();
        $exchangeRateAddData = $exchangeRateService->addExchangeRate($exchangeRateValidation->validate($requestData));
        return new JsonResponse($exchangeRateAddData, 201);
    } catch (
        EmptyFieldException|
        DataExistsException|
        DatabaseNotFoundException|
        IncorrectInputException $e
    ) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        return new JsonResponse($errorDTO, $e->getCode());
    }
});

$app->patch('/exchangeRate[/{currencyPair}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    try {
        $currencyData = new CurrenciesDataGateway($dataBase);
        $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
        $exchangeRateService = new ExchangeRatesService($exchangeRateData);
        $exchangeRateValidation = new ExchangeRateValidatorService($exchangeRateData, $currencyData);
        $data = $request->getParsedBody();
        $exchangeRateValidation->validateRate($data);
        $exchangeRateValidation->validateCurrencyPair($args['currencyPair']);
        $exchangeRateUpdateData = $exchangeRateService->changeExchangeRate($args['currencyPair'], $data);
        return new JsonResponse($exchangeRateUpdateData);
    } catch (EmptyFieldException | IncorrectInputException | DatabaseNotFoundException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        return new JsonResponse($errorDTO, $e->getCode());
    }
});

$app->get('/exchange', function (Request $request) use ($dataBase) {
    try {
        $currenciesData = new CurrenciesDataGateway($dataBase);
        $currenciesService = new CurrenciesService($currenciesData);
        $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
        $exchangeService = new ExchangeService($currenciesService, $exchangeRateData);
        $exchangeValidator = new CurrencyExchangeValidatorService();
        $requestDTO = $exchangeValidator->validate($request->getQueryParams());
        $exchangeData = $exchangeService->currencyExchange($requestDTO);
        return new JsonResponse($exchangeData);
    } catch (DatabaseNotFoundException|IncorrectInputException $e) {
        $errorDTO = new ErrorResponseDTO($e->getMessage());
        return new JsonResponse($errorDTO, $e->getCode());
    }
});

$app->run();