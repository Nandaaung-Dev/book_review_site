<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function success($data = [], $message = 'Success')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], 200);
    }
}
