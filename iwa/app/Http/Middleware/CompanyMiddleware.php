<?php

// app/Http/Middleware/CompanyMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompanyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && in_array(auth()->user()->user_role, [3, 6])) {
            return $next($request);
        }

        abort(403, 'Toegang geweigerd');
    }
}

