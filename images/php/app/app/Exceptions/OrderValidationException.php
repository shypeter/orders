<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Arrayable;

class OrderValidationException extends Exception implements Arrayable
{
    protected $errors;

    public function __construct(
        array $errors,
        string $message = 'The given data was invalid.',
        int $code = 400
    ) {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function toArray()
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ];
    }
}