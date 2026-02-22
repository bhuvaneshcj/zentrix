<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $providedKey = $request->header('X-STUDENT-KEY');

        if (!$providedKey || $providedKey !== config('app.student_key')) {
            return response()->json([
                'message' => 'Unauthorized - Invalid or Missing Student Key'
            ], 401);
        }

        return $next($request);
    }
}