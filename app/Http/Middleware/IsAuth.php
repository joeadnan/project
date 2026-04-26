<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    if (!$request->input('authenticated')) {
        return response()->json([
            'message' => 'Unauthenticated'
        ], 401);
    }

    return $next($request);
}}