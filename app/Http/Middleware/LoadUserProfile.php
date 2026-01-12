<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoadUserProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            // Charger automatiquement la relation selon le rôle
            if ($user->role === 'joueur' && !$user->relationLoaded('joueur')) {
                $user->load('joueur');
            } elseif ($user->role === 'recruteur' && !$user->relationLoaded('recruteur')) {
                $user->load('recruteur');
            }
        }

        return $next($request);
    }
}
