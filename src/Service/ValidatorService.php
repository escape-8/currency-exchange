<?php

namespace App\Service;

use App\Exception\ValidationException;

abstract class ValidatorService
{
    public function checkContainsOnlyLetters(string $string): void
    {
        preg_match_all('/[[:alpha:]]/', $string, $matches, PREG_PATTERN_ORDER);

        if (count($matches[0]) !== strlen($string)) {
            throw new ValidationException("The '$string' must contain only letters", 409);
        }
    }

    public function checkStringLength(string $string, int $stringMaxLength): void
    {
        if (strlen($string) !== $stringMaxLength) {
            throw new ValidationException("The '$string' must contain $stringMaxLength symbols", 409);
        }
    }

    public function checkSpace($string): void
    {
        $nonSpaceString = str_replace(' ', '', $string);
        if (strlen($nonSpaceString) !== strlen($string)) {
            throw new ValidationException("The line '$string' must not contain spaces", 409);
        }
    }

    public function checkIsNumeric(mixed $value): void
    {
        if (!is_numeric($value)) {
            throw new ValidationException("Format '$value' incorrect. Example correct format: 1, 2.61, 0.03021 etc", 409);
        }
    }

    public function checkAllLettersUpperCase(string $string): void
    {
        if ($string !== strtoupper($string)) {
            throw new ValidationException("The line '$string' must be only uppercase letters", 409);
        }
    }
}