<?php

namespace App\Service;

use App\DTO\CurrencyRequestDTO;
use App\Exception\Validation\IncorrectInputException;
use App\Exception\Validation\DataExistsException;
use App\Exception\Validation\EmptyFieldException;
use App\Model\Currency;

class CurrencyValidatorService extends ValidatorService
{

    /**
     * @throws EmptyFieldException
     * @throws DataExistsException
     * @throws IncorrectInputException
     */
    public function validate(array $data): CurrencyRequestDTO
    {
        $this->checkEmptyFields($data);
        $this->checkStringLength($data['code'], Currency::COUNT_LETTERS_IN_CODE);
        $this->checkSpace($data['code']);
        $this->checkContainsOnlyLetters($data['code']);
        $this->checkAllLettersUpperCase($data['code']);

        return new CurrencyRequestDTO($data['code'], $data['name'], $data['sign']);
    }

    /**
     * @throws EmptyFieldException
     */
    public function checkEmptyFields(array $data): void
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors[] = 'name';
        }

        if (empty($data['code'])) {
            $errors[] = 'code';
        }

        if (empty($data['sign'])) {
            $errors[] = 'sign';
        }

        if ($errors) {
            throw new EmptyFieldException($errors);
        }

    }
}