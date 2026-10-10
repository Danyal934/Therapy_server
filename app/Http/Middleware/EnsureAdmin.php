<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('admin.login');
        }

        if (! ($user->admin ?? false)) {
            abort(403);
        }

        return $next($request);
    }
}
