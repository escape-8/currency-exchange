<?php

namespace App\Service;

use App\DataGateway\CurrenciesDataGateway;
use App\DTO\CurrencyRequestDTO;
use App\Exception\Validation\ContainsSpaceException;
use App\Exception\Validation\IncorrectInputException;
use App\Exception\Validation\NotContainsOnlyLettersException;
use App\Exception\Validation\CodeExistsException;
use App\Exception\Validation\InputDataLengthException;
use App\Exception\Validation\EmptyFieldException;
use App\Model\Currency;

class CurrencyValidatorService extends ValidatorService
{
    private CurrenciesDataGateway $dataGateway;

    /**
     * @param CurrenciesDataGateway $dataGateway
     */
    public function __construct(CurrenciesDataGateway $dataGateway)
    {
        $this->dataGateway = $dataGateway;
    }

    /**
     * @throws EmptyFieldException
     * @throws CodeExistsException
     * @throws NotContainsOnlyLettersException
     * @throws InputDataLengthException
     * @throws ContainsSpaceException
     * @throws IncorrectInputException
     */
    public function validate(array $data): CurrencyRequestDTO
    {
        $this->checkEmptyFields($data);
        $this->checkStringLength($data['code'], Currency::COUNT_LETTERS_IN_CODE);
        $this->checkSpace($data['code']);
        $this->checkContainsOnlyLetters($data['code']);
        $this->checkAllLettersUpperCase($data['code']);

        return new CurrencyRequestDTO(strtoupper($data['code']), $data['name'], $data['sign']);
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

    /**
     * @throws CodeExistsException
     */
    protected function checkExistsCurrencyCode(string $currencyCode): void
    {
        if ($this->dataGateway->isCurrencyExists($currencyCode)) {
            throw new CodeExistsException('A currency with this code already exists', $currencyCode);
        }
    }
}