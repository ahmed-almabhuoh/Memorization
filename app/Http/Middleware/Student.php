<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $position = auth()->user()->position;
        if ($position == 'student' or $position == 'parent') {
            return $next($request);
        }

        return \response()->json([
            'message' => 'Unavailable resources'
        ], Response::HTTP_BAD_REQUEST);
    }
}
