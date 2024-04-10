<?php

declare(strict_types=1);

use App\Actions\CurrenciesAction;
use App\Actions\CurrencyAction;
use App\Actions\ExchangeAction;
use App\Actions\ExchangeRateAction;
use App\Actions\ExchangeRatesAction;
use App\Actions\HomeAction;
use App\Actions\PreflightRequestsAction;
use Slim\App;

return static function (App $app) {
    $app->get('/', [HomeAction::class, 'index']);

    $app->get('/currencies', [CurrenciesAction::class, 'show']);

    $app->post('/currencies', [CurrenciesAction::class, 'create']);

    $app->get('/currency[/{currency}]', [CurrencyAction::class, 'show']);

    $app->get('/exchangeRates', [ExchangeRatesAction::class, 'show']);

    $app->post('/exchangeRates', [ExchangeRatesAction::class, 'create']);

    $app->get('/exchangeRate[/{currencyPair}]', [ExchangeRateAction::class, 'show']);

    $app->patch('/exchangeRate[/{currencyPair}]', [ExchangeRateAction::class, 'update']);

    $app->get('/exchange', [ExchangeAction::class, 'exchange']);

    // Allow preflight requests
    $app->options('/{routes:.+}', PreflightRequestsAction::class);
};