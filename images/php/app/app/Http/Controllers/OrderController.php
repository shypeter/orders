<?php
namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Exceptions\OrderValidationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
//use App\Http\Requests\CreateOrderRequest;

class OrderController extends Controller
{
    private $orderService;
    //private $createOrderRequest;

    public function __construct(
        OrderService $orderService
    ) {
        $this->orderService = $orderService;
        //$this->createOrderRequest = $createOrderRequest;
    }

    public function store(Request $request)
    {
        try {
            //lumen bug : Method Illuminate\Validation\Validator::validateRquired does not exist.
            //$validateData = $this->createOrderRequest->validate($request);

            $validateData = $this->validate($request, [
                'id' => 'required|string',
                'name' => 'required|string',
                'address' => 'required|array',
                'address.city' => 'required|string',
                'address.district' => 'required|string',
                'address.street' => 'required|string',
                'price' => 'required|numeric',
                'currency' => 'required|string|in:TWD,USD',
            ]);

            $result = $this->orderService->process($validateData);
            return response()->json($result, 201);
        } catch (ValidationException  $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
                ], 422);
        } catch (OrderValidationException $e) {
            return response()->json([
                'error' => $e->getErrors()
                ], 400);
        }
    }
}