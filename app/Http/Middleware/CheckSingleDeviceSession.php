<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSingleDeviceSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if ($request->user()) {
            // Check if current access token exists (it should, but just in case)
            $currentToken = $request->user()->currentAccessToken();
            if (!$currentToken) {
                return response()->json([
                    'message' => 'Session expired. Please login again.',
                    'logout_required' => true
                ], 401);
            }
        }

        return $next($request);
    }
}
