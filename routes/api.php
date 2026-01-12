<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Joueur\ExperienceController;
use App\Http\Controllers\Joueur\ProfilController;
use App\Http\Controllers\Joueur\VideoController;
use App\Http\Controllers\Recruteur\AnnonceController;
use App\Http\Controllers\Recruteur\CandidatureController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Routes publiques (données de référence)
Route::get('/countries', function () {
    return \App\Models\Country::select('id', 'name_fr', 'name_en', 'iso2')->get();
});

Route::get('/positions', function () {
    return \App\Models\Position::select('id', 'nom_fr', 'nom_en', 'abreviation')->get();
});

// Vérifie le token Sanctum mais ne bloque pas si absent/invalide
Route::middleware(\App\Http\Middleware\OptionalSanctumAuth::class)->group(function () {
    // Routes publiques - Recherche joueurs (avec détection auth optionnelle)
    Route::get('/joueurs/search', [ProfilController::class, 'search']);
    Route::get('/joueurs/{id}', [ProfilController::class, 'show']);

    // Routes publiques - Annonces
    Route::get('/annonces', [AnnonceController::class, 'index']);
    Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
});

// Routes publiques avec rate limiting (protection brute force)
Route::middleware('throttle:auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Routes protégées par l'authentification Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);

    // Informations utilisateur connecté
    Route::get('/me', function (Request $request) {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Non authentifié.'
            ], 401);
        }

        // Charger les relations selon le rôle
        if ($user->estJoueur()) {
            $user->load([
                'joueur.position',
                'joueur.nationality',
                'joueur.clubActuel.country',
                'joueur.videos',
                'joueur.experiences.club.country'
            ]);
        } elseif ($user->estRecruteur()) {
            $user->load([
                'recruteur.country',
                'recruteur.club'
            ]);
        }

        return new \App\Http\Resources\UserResource($user);
    });

    // Routes réservées aux JOUEURS
    Route::middleware('joueur')->group(function () {
        // Gestion du profil joueur
        Route::put('/joueur/profil', [ProfilController::class, 'update']);

        // Gestion des expériences
        Route::apiResource('experiences', ExperienceController::class);

        // Gestion des vidéos
        Route::apiResource('videos', VideoController::class);

        // Candidatures
        Route::get('/mes-candidatures', [CandidatureController::class, 'mesCandidatures']);
        Route::post('/annonces/{annonceId}/candidatures', [CandidatureController::class, 'store']);
        Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy']);
    });

    // Routes réservées aux RECRUTEURS
    Route::middleware('recruteur')->group(function () {
        // Gestion des annonces
        Route::get('/mes-annonces', [AnnonceController::class, 'mesAnnonces']);
        Route::post('/annonces', [AnnonceController::class, 'store']);
        Route::put('/annonces/{id}', [AnnonceController::class, 'update']);
        Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

        // Gestion des candidatures
        Route::get('/annonces/{annonceId}/candidatures', [CandidatureController::class, 'candidaturesRecues']);
        Route::put('/candidatures/{id}/statut', [CandidatureController::class, 'updateStatut']);
    });
});
