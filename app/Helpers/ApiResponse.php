<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = [], $messageKey = 'success', $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => __($messageKey),
            'data' => $data
        ], $code);
    }

    public static function error($messageKey = 'error', $code = 400)
    {
        return response()->json([
            'status' => false,
            'message' => __($messageKey),
        ], $code);
    }
}
