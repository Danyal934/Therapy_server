<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireApiKey
{
    public function handle(Request $request, Closure $next)
    {
        // Skip API key check for shop routes
        $skipRoutes = [
            'api/shop/products',
            'api/shop/orders',
            'api/shop/orders/confirm',
        ];

        foreach ($skipRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }

        if ($request->hasValidSignature()) {
            return $next($request);
        }

        $expected = config('app.api_key');

        if (! is_string($expected) || $expected === '') {
            if (app()->environment('local', 'development')) {
                return $next($request);
            }

            return response()->json([
                'message' => 'API key is not configured.',
            ], 500);
        }

        $provided = $request->header('X-Api-Key');
        if (! is_string($provided) || $provided === '') {
            return response()->json([
                'message' => 'Missing API key.',
            ], 401);
        }

        if (! hash_equals($expected, $provided)) {
            return response()->json([
                'message' => 'Invalid API key.',
            ], 401);
        }

        return $next($request);
    }
}
