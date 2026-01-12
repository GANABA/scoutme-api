<?php

namespace App\Http\Controllers\Joueur;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilRequest;
use App\Http\Resources\PrivatePlayerResource;
use App\Http\Resources\PublicPlayerResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    /**
     * Afficher le profil d'un joueur
     * - Vue publique si non authentifié
     * - Vue complète si authentifié
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $joueur = User::with([
            'joueur.position',
            'joueur.nationality',
            'joueur.clubActuel.country',
            'joueur.videos',
            'joueur.experiences.club.country'
        ])
        ->where('role', 'joueur')
        ->findOrFail($id);

        // Si utilisateur authentifié (lui-même ou recruteur) - vue complète
        if ($request->user() && ($request->user()->id === $id || $request->user()->estRecruteur())) {
            return response()->json(new PrivatePlayerResource($joueur));
        }

        // Sinon - vue publique (partielle)
        return response()->json(new PublicPlayerResource($joueur));
    }

    /**
     * Mettre à jour le profil du joueur connecté
     */
    public function update(UpdateProfilRequest $request): JsonResponse
    {
        $user = $request->user();

        // S'assurer que la relation joueur est chargée
        if (!$user->joueur) {
            return response()->json([
                'message' => 'Profil joueur introuvable.'
            ], 404);
        }

        $data = $request->validated();

        // Séparer les données User et Joueur
        $userData = array_intersect_key($data, array_flip(['first_name', 'last_name']));
        $joueurData = array_diff_key($data, $userData);

        // Mise à jour User
        if (!empty($userData)) {
            $user->update($userData);
        }

        // Mise à jour Joueur
        if (!empty($joueurData)) {
            $user->joueur->update($joueurData);
        }

        // Recharger les relations
        $user->load([
            'joueur.position',
            'joueur.nationality',
            'joueur.clubActuel.country',
            'joueur.videos',
            'joueur.experiences.club.country'
        ]);

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user' => new PrivatePlayerResource($user)
        ]);
    }

    /**
     * Recherche publique de joueurs
     */
    public function search(Request $request): JsonResponse
    {
        $query = User::query()
            ->where('role', 'joueur');

        // Charger la relation joueur avec ses sous-relations de base
        $query->with([
            'joueur.position',
            'joueur.nationality',
            'joueur.clubActuel.country'
        ]);

        // Ajouter les counts via subqueries
        $query->addSelect([
            'videos_count' => DB::table('videos')
                ->join('joueurs', 'videos.joueur_id', '=', 'joueurs.id')
                ->whereColumn('joueurs.user_id', 'users.id')
                ->selectRaw('COUNT(*)'),
            'experiences_count' => DB::table('experiences')
                ->join('joueurs', 'experiences.joueur_id', '=', 'joueurs.id')
                ->whereColumn('joueurs.user_id', 'users.id')
                ->selectRaw('COUNT(*)')
        ]);

        // Filtres
        if ($request->has('position_id')) {
            $query->whereHas('joueur', function ($q) use ($request) {
                $q->where('position_id', $request->position_id);
            });
        }

        if ($request->has('nationality_id')) {
            $query->whereHas('joueur', function ($q) use ($request) {
                $q->where('nationality_id', $request->nationality_id);
            });
        }

        if ($request->has('age_min')) {
            $dateMax = now()->subYears($request->age_min)->format('Y-m-d');
            $query->whereHas('joueur', function ($q) use ($dateMax) {
                $q->where('date_naissance', '<=', $dateMax);
            });
        }

        if ($request->has('age_max')) {
            $dateMin = now()->subYears($request->age_max)->format('Y-m-d');
            $query->whereHas('joueur', function ($q) use ($dateMin) {
                $q->where('date_naissance', '>=', $dateMin);
            });
        }

        if ($request->has('taille_min')) {
            $query->whereHas('joueur', function ($q) use ($request) {
                $q->where('taille', '>=', $request->taille_min);
            });
        }

        if ($request->has('pied_fort')) {
            $query->whereHas('joueur', function ($q) use ($request) {
                $q->where('pied_fort', $request->pied_fort);
            });
        }

        // Recherche par nom
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $joueurs = $query->paginate(20);

        // Vérifier si recruteur authentifié
        $user = $request->user();
        $isAuthRecruteur = $user && $user->estRecruteur();

        if ($isAuthRecruteur) {
            // Charger les relations supplémentaires pour les recruteurs
            $joueurs->load([
                'joueur.videos',
                'joueur.experiences.club.country'
            ]);

            return response()->json([
                'data' => PrivatePlayerResource::collection($joueurs->items()),
                'meta' => [
                    'current_page' => $joueurs->currentPage(),
                    'last_page' => $joueurs->lastPage(),
                    'per_page' => $joueurs->perPage(),
                    'total' => $joueurs->total(),
                ]
            ]);
        }

        // Vue publique
        return response()->json([
            'data' => PublicPlayerResource::collection($joueurs->items()),
            'meta' => [
                'current_page' => $joueurs->currentPage(),
                'last_page' => $joueurs->lastPage(),
                'per_page' => $joueurs->perPage(),
                'total' => $joueurs->total(),
            ]
        ]);
    }
}
