<?php

namespace App\Service;

use App\Exception\Validation\ContainsSpaceException;
use App\Exception\Validation\InputDataLengthException;
use App\Exception\Validation\NotContainsOnlyLettersException;

abstract class ValidatorService
{
    /**
     * @throws NotContainsOnlyLettersException
     */
    public function checkContainsOnlyLetters(string $string): void
    {
        preg_match_all('/[[:alpha:]]/', $string, $matches, PREG_PATTERN_ORDER);

        if (count($matches[0]) !== strlen($string)) {
            throw new NotContainsOnlyLettersException($string);
        }
    }

    /**
     * @throws InputDataLengthException
     */
    public function checkStringLength(string $string, int $stringMaxLength): void
    {
        if (strlen($string) !== $stringMaxLength) {
            throw new InputDataLengthException($string , $stringMaxLength);
        }
    }

    /**
     * @throws ContainsSpaceException
     */
    public function checkSpace($string): void
    {
        $nonSpaceString = str_replace(' ', '', $string);
        if (strlen($nonSpaceString) !== strlen($string)) {
            throw new ContainsSpaceException($string);
        }
    }
}