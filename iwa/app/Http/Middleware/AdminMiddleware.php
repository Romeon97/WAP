<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Je moet ingelogd zijn.');
        }

        if (!in_array(Auth::user()->user_role, [4, 6])) {
            return redirect('/')->with('error', 'Je hebt geen toegang tot deze pagina.');
        }

        return $next($request);
    }
}
