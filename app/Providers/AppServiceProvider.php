<?php

namespace App\Providers;

use App\Models\AnnonceRecrutement;
use App\Models\Candidature;
use App\Models\Experience;
use App\Models\Video;
use App\Policies\AnnoncePolicy;
use App\Policies\CandidaturePolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\VideoPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rate limiter pour les routes d'authentification
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5) // 5 tentatives par minute
                ->by($request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Trop de tentatives. Veuillez réessayer dans quelques instants.',
                    ], 429, $headers);
                });
        });

        // Rate limiter pour l'API générale
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60) // 60 requêtes par minute
                ->by($request->user()?->id ?: $request->ip());
        });

        // Enregistrement des politiques d'autorisation
        Gate::policy(Video::class, VideoPolicy::class);
        Gate::policy(Experience::class, ExperiencePolicy::class);
        Gate::policy(AnnonceRecrutement::class, AnnoncePolicy::class);
        Gate::policy(Candidature::class, CandidaturePolicy::class);
    }
}
