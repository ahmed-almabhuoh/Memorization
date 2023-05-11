<?php

namespace App\Http\Middleware\api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AbsenceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!(auth()->user()->position == 'keeper' || auth()->user()->position == 'student')) {
            abort(403, 'Unavilable resource');
        }
        return $next($request);
    }
}
