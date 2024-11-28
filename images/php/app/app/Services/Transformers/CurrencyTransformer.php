<?php
namespace App\Services\Transformers;

use App\Contracts\OrderTransformer;

class CurrencyTransformer implements OrderTransformer
{
    private const USD_TO_TWD_DATE = 31;

    public function transform(array $data): array
    {
        if ($data['currency'] === 'USD') {
            $data['price'] = $data['price'] * self::USD_TO_TWD_DATE;
            $data['currency'] = 'TWD';
        }

        return $data;
    }
}