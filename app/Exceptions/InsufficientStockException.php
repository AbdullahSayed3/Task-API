<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    protected $message = 'Insufficient stock for the requested product';
    protected $code = 400;

    public function __construct(string $productName = '')
    {
        if ($productName) {
            $this->message = "Insufficient stock for {$productName}";
        }
        parent::__construct($this->message, $this->code);
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'message' => $this->message
        ], $this->code);
    }
}