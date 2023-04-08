<?php

namespace App\Http\Middleware\api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteAccountMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->deleted_at) {
            return redirect()->route('logout')->with([
                'message' => 'Your account has been deleted before!'
            ]);
//            return \response()->json([
//                'message' => 'Your account has been deleted before!',
//            ], Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
