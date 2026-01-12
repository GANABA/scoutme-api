<?php

namespace App\Http\Controllers\Joueur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Liste des expériences du joueur connecté
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // S'assurer que la relation joueur est chargée
        if (!$user->joueur) {
            return response()->json([
                'message' => 'Profil joueur introuvable.'
            ], 404);
        }

        $experiences = $user->joueur->experiences()->with('club.country')->get();

        return response()->json([
            'data' => ExperienceResource::collection($experiences)
        ]);
    }

    /**
     * Ajouter une expérience
     */
    public function store(StoreExperienceRequest $request): JsonResponse
    {
        $user = $request->user();

        $experience = Experience::create([
            'joueur_id' => $user->joueur->id,
            ...$request->validated()
        ]);

        $experience->load('club.country');

        return response()->json([
            'message' => 'Expérience ajoutée avec succès.',
            'data' => new ExperienceResource($experience)
        ], 201);
    }

    /**
     * Afficher une expérience spécifique
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $experience = Experience::with('club.country')->findOrFail($id);

        // Utiliser la Policy
        $this->authorize('view', $experience);

        return response()->json([
            'data' => new ExperienceResource($experience)
        ]);
    }

    /**
     * Mettre à jour une expérience
     */
    public function update(UpdateExperienceRequest $request, int $id): JsonResponse
    {
        $experience = Experience::findOrFail($id);

        // Utilisation de la Policy
        $this->authorize('update', $experience);

        $experience->update($request->validated());
        $experience->load('club.country');

        return response()->json([
            'message' => 'Expérience mise à jour avec succès.',
            'data' => new ExperienceResource($experience)
        ]);
    }

    /**
     * Supprimer une expérience
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $experience = Experience::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('delete', $experience);

        $experience->delete();

        return response()->json([
            'message' => 'Expérience supprimée avec succès.'
        ]);
    }
}
