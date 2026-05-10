<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (!$request->input('membership')) {
        return response()->json([
            'message' => 'Membership required'
        ], 403);
    }

        // ✅ Perbaiki typo: $request-> membership (ada spasi) → $request->membership
        Log::info('Before Request:', [
            'url'    => $request->url(),
            'params' => $request->all(),
        ]);

        $response = $next($request); // ✅ Typo: $respone → $response

        sleep(2);

        Log::info('After Request:', [
            'status'  => $response->getStatusCode(),
            'content' => $response->getContent(),
        ]);

        return $response; // ✅ Typo: $respone → $response
    }
}