<?php

namespace App\Service;

use App\Exception\Validation\IncorrectInputException;

abstract class ValidatorService
{
    /**
     * @throws IncorrectInputException
     */
    public function checkContainsOnlyLetters(string $string): void
    {
        preg_match_all('/[[:alpha:]]/', $string, $matches, PREG_PATTERN_ORDER);

        if (count($matches[0]) !== strlen($string)) {
            throw new IncorrectInputException("The '$string' must contain only letters");
        }
    }

    /**
     * @throws IncorrectInputException
     */
    public function checkStringLength(string $string, int $stringMaxLength): void
    {
        if (strlen($string) !== $stringMaxLength) {
            throw new IncorrectInputException("The '$string' must contain $stringMaxLength symbols");
        }
    }

    /**
     * @throws IncorrectInputException
     */
    public function checkSpace($string): void
    {
        $nonSpaceString = str_replace(' ', '', $string);
        if (strlen($nonSpaceString) !== strlen($string)) {
            throw new IncorrectInputException("The line '$string' must not contain spaces");
        }
    }

    /**
     * @throws IncorrectInputException
     */
    public function checkIsNumeric(mixed $value): void
    {
        if (!is_numeric($value)) {
            throw new IncorrectInputException($value);
        }
    }

    /**
     * @throws IncorrectInputException
     */
    public function checkAllLettersUpperCase(string $string): void
    {
        if ($string !== strtoupper($string)) {
            throw new IncorrectInputException("The line '$string' must be only uppercase letters");
        }
    }
}