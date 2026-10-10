<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeConfigController extends Controller
{
    public function show(Request $request)
    {
        $key = env('STRIPE_PUBLISHABLE_KEY');
        if (! is_string($key) || $key === '') {
            return response()->json([
                'message' => 'Stripe publishable key is not configured.',
            ], 500);
        }

        return response()->json([
            'publishable_key' => $key,
        ]);
    }
}
