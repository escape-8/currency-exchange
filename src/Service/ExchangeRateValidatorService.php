<?php

namespace App\Service;

use App\DataGateway\CurrenciesDataGateway;
use App\DataGateway\ExchangeRatesDataGateway;
use App\DTO\ExchangeRateRequestDTO;
use App\Exception\DatabaseNotFoundException;
use App\Exception\Validation\DataExistsException;
use App\Exception\Validation\EmptyFieldException;
use App\Exception\Validation\IncorrectInputException;
use App\Model\Currency;
use App\Model\ExchangeRate;

class ExchangeRateValidatorService extends ValidatorService
{
    private ExchangeRatesDataGateway $exchangeRatesDataGateway;
    private CurrenciesDataGateway $currenciesDataGateway;

    /**
     * @param ExchangeRatesDataGateway $exchangeRatesDataGateway
     * @param CurrenciesDataGateway $currenciesDataGateway
     */
    public function __construct(ExchangeRatesDataGateway $exchangeRatesDataGateway, CurrenciesDataGateway $currenciesDataGateway)
    {
        $this->exchangeRatesDataGateway = $exchangeRatesDataGateway;
        $this->currenciesDataGateway = $currenciesDataGateway;
    }

    /**
     * @throws DatabaseNotFoundException
     * @throws EmptyFieldException
     * @throws DataExistsException
     * @throws IncorrectInputException
     */
    public function validate(array $data): ExchangeRateRequestDTO
    {
        $this->checkEmptyFields($data);
        $this->checkStringLength($data['baseCurrencyCode'], Currency::COUNT_LETTERS_IN_CODE);
        $this->checkStringLength($data['targetCurrencyCode'], Currency::COUNT_LETTERS_IN_CODE);
        $this->checkSpace($data['baseCurrencyCode']);
        $this->checkSpace($data['targetCurrencyCode']);
        $this->checkContainsOnlyLetters($data['baseCurrencyCode']);
        $this->checkContainsOnlyLetters($data['targetCurrencyCode']);
        $this->checkAllLettersUpperCase($data['baseCurrencyCode']);
        $this->checkAllLettersUpperCase($data['targetCurrencyCode']);
        $this->checkIsNumeric($data['rate']);
        $this->validateRateNumericSyntax($data['rate']);

        if (!$this->currenciesDataGateway->isCurrencyExists($data['baseCurrencyCode'])) {
            throw new DatabaseNotFoundException(
                'The ' . $data['baseCurrencyCode'] . ' currency from the currency pair does not exist in the database'
            );
        }

        if (!$this->currenciesDataGateway->isCurrencyExists($data['targetCurrencyCode'])) {
            throw new DatabaseNotFoundException(
                'The ' . $data['targetCurrencyCode'] . ' currency from the currency pair does not exist in the database'
            );
        }

        if ($this->exchangeRatesDataGateway->isExchangeRateExists($data['baseCurrencyCode'], $data['targetCurrencyCode'])) {
            throw new DataExistsException('A currency pair with this codes already exists: ' . $data['baseCurrencyCode'] . $data['targetCurrencyCode']);
        }

        return new ExchangeRateRequestDTO(
            $this->currenciesDataGateway->getCurrency($data['baseCurrencyCode'])['id'],
            $this->currenciesDataGateway->getCurrency($data['targetCurrencyCode'])['id'],
            $data['rate']
        );
    }

    /**
     * @throws DatabaseNotFoundException
     * @throws IncorrectInputException
     */
    public function validateCurrencyPair(string $currencyPair): void
    {
        if (strlen($currencyPair) !== ExchangeRate::COUNT_LETTERS_IN_EXCHANGE_RATE_CODE) {
            throw new IncorrectInputException(
                'The currency pair must contain uppercase letters. ' .
                '3 letters – base currency and 3 letters – target currency. For example: EURUSD, USDRUB, BTCUSD'
            );
        }

        [$baseCurrencyCode, $targetCurrencyCode] = str_split($currencyPair, Currency::COUNT_LETTERS_IN_CODE);

        if (!$this->exchangeRatesDataGateway->isExchangeRateExists($baseCurrencyCode, $targetCurrencyCode)) {
            throw new DatabaseNotFoundException("Currency pair $currencyPair is not in the database");
        }
    }

    /**
     * @throws IncorrectInputException
     */
    public function validateRateNumericSyntax(string $rate): void
    {
        preg_match('/^0\d/', $rate, $matches);
        if (count($matches) > 0) {
            throw new IncorrectInputException("Format '$rate' incorrect. Example correct format: 1, 2.61, 0.03021 etc");
        }
    }

    /**
     * @throws EmptyFieldException
     */
    public function checkEmptyFields(array $data): void
    {
        $errors = [];

        if (empty($data['baseCurrencyCode'])) {
            $errors[] = 'Base currency';
        }

        if (empty($data['targetCurrencyCode'])) {
            $errors[] = 'Target currency';
        }

        if (empty($data['rate'])) {
            $errors[] = 'Exchange Rate';
        }

        if ($errors) {
            throw new EmptyFieldException($errors);
        }

    }

}