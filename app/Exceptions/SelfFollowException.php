<?php

namespace App\Exceptions;

use Exception;

class SelfFollowException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'Você não pode seguir a si mesmo.'
        ], 403);
    }
}