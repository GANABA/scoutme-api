<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsRecruteur
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->estRecruteur()) {
            return response()->json([
                'message' => 'Accès refusé. Cette ressource est réservée aux recruteurs.'
            ], 403);
        }

        return $next($request);
    }
}
