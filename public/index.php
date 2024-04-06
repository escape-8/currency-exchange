<?php

declare(strict_types=1);

use App\DataGateway\CurrenciesDataGateway;
use App\DataGateway\ExchangeRatesDataGateway;
use App\Http\JsonResponse;
use App\Service\CurrenciesService;
use App\Service\CurrencyExchangeValidatorService;
use App\Service\CurrencyValidatorService;
use App\Service\ExchangeRatesService;
use App\Service\ExchangeRateValidatorService;
use App\Service\ExchangeService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

(require __DIR__ . '/../config/middleware.php')($app);

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
    $currenciesData = new CurrenciesDataGateway($dataBase);
    $currenciesService = new CurrenciesService($currenciesData);
    $currencyData = $currenciesService->getCurrency($args['currency']);
    return new JsonResponse($currencyData);
});

$app->post('/currencies', function (Request $request) use ($dataBase) {
    $currenciesData = new CurrenciesDataGateway($dataBase);
    $currenciesService = new CurrenciesService($currenciesData);
    $currencyValidation = new CurrencyValidatorService();
    $requestData = $request->getParsedBody();
    $currencyAddData = $currenciesService->addCurrency($currencyValidation->validate($requestData));
    return new JsonResponse($currencyAddData, 201);
});

$app->get('/exchangeRates', function () use ($dataBase) {
    $currenciesData = new ExchangeRatesDataGateway($dataBase);
    $currenciesService = new ExchangeRatesService($currenciesData);
    $currenciesData = $currenciesService->getAllExchangeRates();
    return new JsonResponse($currenciesData);
});

$app->get('/exchangeRate[/{currencyPair}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
    $exchangeRateService = new ExchangeRatesService($exchangeRateData);
    $exchangeRateData = $exchangeRateService->getExchangeRateByCurrencyPairCode($args['currencyPair']);
    return new JsonResponse($exchangeRateData);
});

$app->post('/exchangeRates', function (Request $request) use ($dataBase) {
    $currencyData = new CurrenciesDataGateway($dataBase);
    $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
    $exchangeRateService = new ExchangeRatesService($exchangeRateData);
    $exchangeRateValidation = new ExchangeRateValidatorService($currencyData);
    $requestData = $request->getParsedBody();
    $exchangeRateAddData = $exchangeRateService->addExchangeRate($exchangeRateValidation->validate($requestData));
    return new JsonResponse($exchangeRateAddData, 201);
});

$app->patch('/exchangeRate[/{currencyPair}]', function (Request $request, Response $response, array $args) use ($dataBase) {
    $currencyData = new CurrenciesDataGateway($dataBase);
    $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
    $exchangeRateService = new ExchangeRatesService($exchangeRateData);
    $exchangeRateValidation = new ExchangeRateValidatorService($currencyData);
    $data = $request->getParsedBody();
    $exchangeRateValidation->validateRate($data);
    $exchangeRateUpdateData = $exchangeRateService->changeExchangeRate($args['currencyPair'], $data);
    return new JsonResponse($exchangeRateUpdateData);
});

$app->get('/exchange', function (Request $request) use ($dataBase) {
    $currenciesData = new CurrenciesDataGateway($dataBase);
    $currenciesService = new CurrenciesService($currenciesData);
    $exchangeRateData = new ExchangeRatesDataGateway($dataBase);
    $exchangeService = new ExchangeService($currenciesService, $exchangeRateData);
    $exchangeValidator = new CurrencyExchangeValidatorService();
    $requestDTO = $exchangeValidator->validate($request->getQueryParams());
    $exchangeData = $exchangeService->currencyExchange($requestDTO);
    return new JsonResponse($exchangeData);
});

$app->run();