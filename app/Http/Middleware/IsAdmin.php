<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    
    public function handle($request, Closure $next)
    {
        if (auth('admin')->check()) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}