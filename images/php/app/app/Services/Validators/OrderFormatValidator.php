<?php

namespace App\Services\Validators;

use App\Contracts\OrderValidator;
use App\Exceptions\OrderValidationException;

class OrderFormatValidator implements OrderValidator
{
    public function validate(array $data): array
    {
        $errors = [];

        if ($this->containsNonEnglishChars($data['name'])) {
            $errors['name'][] = 'Name contains non-English characters';
        }

        if (!$this->isCapitalized($data['name'])) {
            $errors['name'][] = 'One of name string is not capitalized';
        }

        if ($data['price'] > 2000) {
            $errors['price'][] = 'Price is over 2000';
        }

        if (!in_array($data['currency'], ['TWD', 'USD'])) {
            $errors['currency'][] = 'Currency is not TWD or USD';
        }

        if (!empty($errors)) {
            throw new OrderValidationException($errors);
        }

        return $data;
    }

    private function containsNonEnglishChars(string $name): bool
    {
        return preg_match('/[^A-Za-z\s]/', $name);
    }

    private function isCapitalized(string $name): bool
    {
        $strArr = preg_split('/\s+/', $name);
        foreach ($strArr as $str) {
            if (ucfirst($str) !== $str) {
                return false;
            }
        }
        return true;
    }
}