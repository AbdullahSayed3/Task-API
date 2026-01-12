<?php

namespace App\Exceptions;

use Exception;

class ProductNotAvailableException extends Exception
{
    protected $message = 'Product is not available';
    protected $code = 404;

    public function __construct(string $productId = '')
    {
        if ($productId) {
            $this->message = "Product ID {$productId} is not available";
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