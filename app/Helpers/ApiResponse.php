<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $code);
    }

    public static function error($data = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'data' => $data,
        ], $code);
    }
}
