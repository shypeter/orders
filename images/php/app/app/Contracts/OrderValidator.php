<?php
namespace App\Contracts;

interface OrderValidator
{
    public function validate(array $data): array;
}