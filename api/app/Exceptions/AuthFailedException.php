<?php

namespace App\Exceptions;

use Exception;

class AuthFailedException extends Exception
{
    public function render()
    {
        return response()->json([
            'message' => 'Authentication failed. Invalid credentials.',
        ], 401);
    }
}