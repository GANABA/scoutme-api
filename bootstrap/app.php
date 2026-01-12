<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Configurer le middleware pour les API stateful
        $middleware->statefulApi();
        // Enregistrer les middleware personnalisés avec des alias
        $middleware->alias([
            'joueur' => \App\Http\Middleware\EnsureUserIsJoueur::class,
            'recruteur' => \App\Http\Middleware\EnsureUserIsRecruteur::class,
        ]);

        // LoadUserProfile
        $middleware->append(\App\Http\Middleware\LoadUserProfile::class);

        // Configuration du middleware API
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Force les réponses JSON pour les routes API
        $middleware->prependToGroup('api', \App\Http\Middleware\ForceJsonResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
