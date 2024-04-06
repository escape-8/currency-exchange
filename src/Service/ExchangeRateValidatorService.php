<?php

namespace App\Service;

use App\DataGateway\CurrenciesDataGateway;
use App\DTO\ExchangeRateRequestDTO;
use App\Exception\ValidationException;
use App\Model\Currency;

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

    public function validateRate(array $data): void
    {
        $errors = [];

        if (empty($data['rate'])) {
            $errors[] = 'Exchange Rate';
        }

        if ($errors) {
            throw new ValidationException('A required form field is missing' . ' : ' . implode(', ', $errors), 400);
        }

        $this->checkIsNumeric($data['rate']);
        $this->validateRateNumericSyntax($data['rate']);
    }

    public function validateRateNumericSyntax(string $rate): void
    {
        preg_match('/^0\d/', $rate, $matches);
        if (count($matches) > 0) {
            throw new ValidationException("Format '$rate' incorrect. Example correct format: 1, 2.61, 0.03021 etc", 409);
        }
    }

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
            throw new ValidationException('A required form field is missing' . ' : ' . implode(', ', $errors), 400);
        }

    }

}