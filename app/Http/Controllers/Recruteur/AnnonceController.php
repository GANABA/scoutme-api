<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnonceRequest;
use App\Http\Requests\UpdateAnnonceRequest;
use App\Http\Resources\PrivateAnnonceResource;
use App\Http\Resources\PublicAnnonceResource;
use App\Models\AnnonceRecrutement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Liste publique des annonces (visiteurs et authentifiés)
     */
    public function index(Request $request): JsonResponse
    {
        $query = AnnonceRecrutement::query()
            ->with(['recruteur.recruteur.country', 'club.country', 'country', 'position'])
            ->publiees()
            ->publiques()
            ->ouvertes()
            ->latest();

        // Filtres optionnels
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->has('position_id')) {
            $query->where('position_id', $request->position_id);
        }

        $annonces = $query->paginate(20);

        return response()->json([
            'data' => PublicAnnonceResource::collection($annonces->items()),
            'meta' => [
                'current_page' => $annonces->currentPage(),
                'last_page' => $annonces->lastPage(),
                'per_page' => $annonces->perPage(),
                'total' => $annonces->total(),
            ]
        ]);
    }

    /**
     * Mes annonces (recruteur connecté)
     */
    public function mesAnnonces(Request $request): JsonResponse
    {
        $user = $request->user();

        $annonces = AnnonceRecrutement::query()
            ->with(['recruteur.recruteur.country', 'club.country', 'country', 'position'])
            ->withCount([
                'candidatures',
                'candidatures as candidatures_envoyees_count' => fn($q) => $q->where('statut', 'envoyee'),
                'candidatures as candidatures_en_cours_count' => fn($q) => $q->where('statut', 'en_cours'),
                'candidatures as candidatures_retenues_count' => fn($q) => $q->where('statut', 'retenue'),
                'candidatures as candidatures_refusees_count' => fn($q) => $q->where('statut', 'refusee'),
            ])
            ->where('recruteur_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => PrivateAnnonceResource::collection($annonces)
        ]);
    }

    /**
     * Créer une annonce (recruteur uniquement)
     */
    public function store(StoreAnnonceRequest $request): JsonResponse
    {
        $user = $request->user();

        $annonce = AnnonceRecrutement::create([
            'recruteur_id' => $user->id,
            ...$request->validated()
        ]);

        $annonce->load(['recruteur.recruteur.country', 'club.country', 'country', 'position']);

        return response()->json([
            'message' => 'Annonce créée avec succès.',
            'data' => new PrivateAnnonceResource($annonce)
        ], 201);
    }

    /**
     * Afficher une annonce
     * - Vue publique si non propriétaire
     * - Vue complète si propriétaire
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $annonce = AnnonceRecrutement::with([
            'recruteur.recruteur.country',
            'club.country',
            'country',
            'position',
            'candidatures.joueur.joueur'
        ])->findOrFail($id);

        // Si le recruteur propriétaire - vue complète
        if ($request->user()?->id === $annonce->recruteur_id) {
            // Charger les counts pour la vue privée
            $annonce->loadCount([
                'candidatures',
                'candidatures as candidatures_envoyees_count' => fn($q) => $q->where('statut', 'envoyee'),
                'candidatures as candidatures_en_cours_count' => fn($q) => $q->where('statut', 'en_cours'),
                'candidatures as candidatures_retenues_count' => fn($q) => $q->where('statut', 'retenue'),
                'candidatures as candidatures_refusees_count' => fn($q) => $q->where('statut', 'refusee'),
            ]);

            return response()->json(new PrivateAnnonceResource($annonce));
        }

        // Sinon → vue publique
        return response()->json(new PublicAnnonceResource($annonce));
    }

    /**
     * Mettre à jour une annonce
     */
    public function update(UpdateAnnonceRequest $request, int $id): JsonResponse
    {
        $annonce = AnnonceRecrutement::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('update', $annonce);

        $annonce->update($request->validated());
        $annonce->load(['recruteur.recruteur.country', 'club.country', 'country', 'position']);

        return response()->json([
            'message' => 'Annonce mise à jour avec succès.',
            'data' => new PrivateAnnonceResource($annonce)
        ]);
    }

    /**
     * Supprimer une annonce
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $annonce = AnnonceRecrutement::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('delete', $annonce);

        $annonce->delete();

        return response()->json([
            'message' => 'Annonce supprimée avec succès.'
        ]);
    }
}
