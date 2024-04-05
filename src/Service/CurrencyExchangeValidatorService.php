<?php

namespace App\Service;

use App\DTO\CurrencyExchangeRequestDTO;

class CurrencyExchangeValidatorService extends ValidatorService
{
    public function validate(array $requestData): CurrencyExchangeRequestDTO
    {
        $this->checkIsNumeric($requestData['amount']);

        return new CurrencyExchangeRequestDTO(
            $requestData['from'],
            $requestData['to'],
            $requestData['amount']
        );
    }
}