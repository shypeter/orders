<?php
namespace App\Contracts;

interface OrderTransformer
{
    public function transform(array $data): array;
}