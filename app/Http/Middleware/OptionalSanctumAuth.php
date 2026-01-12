<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class OptionalSanctumAuth
{
    /**
     * Handle an incoming request.
     * Tente d'authentifier via Sanctum, mais ne bloque pas si échec.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer le token depuis le header Authorization
        $token = $request->bearerToken();

        if ($token) {
            // Chercher le token dans la table personal_access_tokens
            $accessToken = PersonalAccessToken::findToken($token);

            if ($accessToken) {
                // Authentifier l'utilisateur via le guard sanctum
                $user = $accessToken->tokenable;

                if ($user) {
                    // Définir l'utilisateur authentifié pour cette requête
                    Auth::setUser($user);
                    $request->setUserResolver(fn() => $user);
                }
            }
        }

        return $next($request);
    }
}
