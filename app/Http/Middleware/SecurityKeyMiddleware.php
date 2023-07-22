<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityKeyMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if ($request->key !== env('SECURITY_KEY')) {
            abort(403);
        }
        return $next($request);
    }
}
