<?php
namespace App\Services;

use App\Contracts\OrderValidator;
use App\Contracts\OrderTransformer;

class OrderService
{
    private $validator;
    private $transformer;

    public function __construct(
        OrderValidator $validator,
        OrderTransformer $transformer
    ) {
        $this->validator = $validator;
        $this->transformer = $transformer;
    }

    public function process(array $orderData): array
    {
        $validatedData = $this->validator->validate($orderData);
        return $this->transformer->transform($validatedData);
    }
}