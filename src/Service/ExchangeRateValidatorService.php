<?php

namespace App\Service;

use App\DataGateway\CurrenciesDataGateway;
use App\DTO\ExchangeRateRequestDTO;
use App\Exception\DatabaseNotFoundException;
use App\Exception\Validation\DataExistsException;
use App\Exception\Validation\EmptyFieldException;
use App\Exception\Validation\IncorrectInputException;
use App\Model\Currency;
use App\Model\ExchangeRate;

class ExchangeRateValidatorService extends ValidatorService
{
    private CurrenciesDataGateway $currenciesDataGateway;

    /**
     * @param CurrenciesDataGateway $currenciesDataGateway
     */
    public function __construct(CurrenciesDataGateway $currenciesDataGateway)
    {
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

        return new ExchangeRateRequestDTO(
            $this->currenciesDataGateway->getCurrency($data['baseCurrencyCode'])['id'],
            $this->currenciesDataGateway->getCurrency($data['targetCurrencyCode'])['id'],
            $data['rate']
        );
    }

    /**
     * @throws IncorrectInputException
     * @throws EmptyFieldException
     */
    public function validateRate(array $data): void
    {
        $errors = [];

        if (empty($data['rate'])) {
            $errors[] = 'Exchange Rate';
        }

        if ($errors) {
            throw new EmptyFieldException($errors);
        }

        $this->checkIsNumeric($data['rate']);
        $this->validateRateNumericSyntax($data['rate']);
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