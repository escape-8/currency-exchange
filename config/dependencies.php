<?php

declare(strict_types=1);

use App\Actions\CurrenciesAction;
use App\Actions\CurrencyAction;
use App\Actions\ExchangeAction;
use App\Actions\ExchangeRateAction;
use App\Actions\ExchangeRatesAction;
use App\Database\DatabaseConnection;
use App\DataGateway\CurrenciesDataGateway;
use App\DataGateway\ExchangeRatesDataGateway;
use App\Service\CurrenciesService;
use App\Service\CurrencyExchangeValidatorService;
use App\Service\CurrencyValidatorService;
use App\Service\ExchangeRatesService;
use App\Service\ExchangeRateValidatorService;
use App\Service\ExchangeService;
use Psr\Container\ContainerInterface;


return static function (ContainerInterface $container) {

    $container->set('settings', function () {
        return require __DIR__ . '/settings.php';
    });


    $container->set('PDO', function (ContainerInterface $container) {
        $connection = new DatabaseConnection($container->get('settings')['db']);
        return $connection->getConnection();
    });

    $container->set('CurrenciesDataGateway', function (ContainerInterface $container) {
        return new CurrenciesDataGateway($container->get('PDO'));
    });

    $container->set('CurrenciesService', function (ContainerInterface $container) {
        return new CurrenciesService($container->get('CurrenciesDataGateway'));
    });

    $container->set('CurrencyValidatorService', function () {
        return new CurrencyValidatorService();
    });

    $container->set('ExchangeRatesDataGateway', function (ContainerInterface $container) {
        return new ExchangeRatesDataGateway($container->get('PDO'));
    });

    $container->set('ExchangeRatesService', function (ContainerInterface $container) {
        return new ExchangeRatesService($container->get('ExchangeRatesDataGateway'));
    });

    $container->set('ExchangeRateValidatorService', function (ContainerInterface $container) {
        return new ExchangeRateValidatorService($container->get('CurrenciesDataGateway'));
    });

    $container->set('ExchangeService', function (ContainerInterface $container) {
        return new ExchangeService($container->get('CurrenciesService'), $container->get('ExchangeRatesDataGateway'));
    });

    $container->set('CurrencyExchangeValidatorService', function () {
        return new CurrencyExchangeValidatorService();
    });

    $container->set('ExchangeRatesAction', function (ContainerInterface $container) {
        return new ExchangeRatesAction($container->get('CurrenciesService'), $container->get('ExchangeRateValidatorService'));
    });

    $container->set('CurrenciesAction', function (ContainerInterface $container) {
        return new CurrenciesAction($container->get('CurrenciesService'), $container->get('CurrencyValidatorService'));
    });

    $container->set('CurrencyAction', function (ContainerInterface $container) {
        return new CurrencyAction($container->get('CurrenciesService'));
    });

    $container->set('ExchangeRatesAction', function (ContainerInterface $container) {
        return new ExchangeRatesAction($container->get('CurrenciesService'), $container->get('ExchangeRateValidatorService'));
    });

    $container->set('ExchangeRateAction', function (ContainerInterface $container) {
        return new ExchangeRateAction($container->get('ExchangeRatesService'), $container->get('ExchangeRateValidatorService'));
    });

    $container->set('ExchangeAction', function (ContainerInterface $container) {
        return new ExchangeAction($container->get('ExchangeService'), $container->get('CurrencyExchangeValidatorService'));
    });
};