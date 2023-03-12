<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Keeper
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->position === 'keeper') {
            return $next($request);
        }

        return \response()->json([
            'message' => 'Unavailable resources'
        ], Response::HTTP_BAD_REQUEST);
    }
}
