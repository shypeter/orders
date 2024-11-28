<?php
namespace App\Http\Requests;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Factory as ValidationFactory;

class CreateOrderRequest extends Request
{
    private $validationFactory;

    public function __construct(ValidationFactory $validationFactory)
    {
        $this->validationFactory = $validationFactory;
    }

    public function rules()
    {
        return [
            'id' => 'rquired|string',
            'name' => 'required|string',
            'address' => 'required|array',
            'address.city' => 'required|string',
            'address.distric' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|in:TWD,USD',
        ];
    }

    public function validate(Request $request = null)
    {
        try {
            $data = $request ? $request->all() : app('request')->all();
            return $this->validationFactory->validate(
                $data,
                $this->rules()
            );
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}