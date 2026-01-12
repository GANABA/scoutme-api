<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Resources\CandidatureResource;
use App\Models\AnnonceRecrutement;
use App\Models\Candidature;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CandidatureController extends Controller
{
    use AuthorizesRequests;

    /**
     * Mes candidatures (joueur connecté)
     */
    public function mesCandidatures(Request $request): JsonResponse
    {
        $user = $request->user();

        $candidatures = Candidature::query()
            ->with(['annonce.recruteur.recruteur', 'annonce.club', 'annonce.country'])
            ->where('joueur_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => CandidatureResource::collection($candidatures)
        ]);
    }

    /**
     * Candidatures reçues pour mes annonces (recruteur connecté)
     */
    public function candidaturesRecues(Request $request, int $annonceId): JsonResponse
    {
        $annonce = AnnonceRecrutement::findOrFail($annonceId);

        // Utiliser la Policy
        $this->authorize('viewCandidatures', $annonce);

        $candidatures = $annonce->candidatures()
            ->with([
                'joueur.joueur.position',
                'joueur.joueur.nationality',
                'joueur.joueur.clubActuel',
                'joueur.joueur.videos',
                'joueur.joueur.experiences'
            ])
            ->latest()
            ->get();

        return response()->json([
            'data' => CandidatureResource::collection($candidatures)
        ]);
    }

    /**
     * Postuler à une annonce (joueur uniquement)
     */
    public function store(StoreCandidatureRequest $request, int $annonceId): JsonResponse
    {
        $user = $request->user();

        $annonce = AnnonceRecrutement::findOrFail($annonceId);

        // Vérifier que l'annonce est ouverte
        if (!$annonce->estOuverte()) {
            return response()->json([
                'message' => 'Cette annonce n\'est plus ouverte aux candidatures.'
            ], 422);
        }

        // Vérifier que le joueur n'a pas déjà postulé
        if ($annonce->aDejaPostule($user->id)) {
            return response()->json([
                'message' => 'Vous avez déjà postulé à cette annonce.'
            ], 422);
        }

        $candidature = Candidature::create([
            'annonce_id' => $annonceId,
            'joueur_id' => $user->id,
            'message' => $request->validated('message'),
            'statut' => 'envoyee',
        ]);

        $candidature->load(['annonce.recruteur.recruteur', 'annonce.club']);

        return response()->json([
            'message' => 'Candidature envoyée avec succès.',
            'data' => new CandidatureResource($candidature)
        ], 201);
    }

    /**
     * Mettre à jour le statut d'une candidature (recruteur uniquement)
     */
    public function updateStatut(Request $request, int $id): JsonResponse
    {
        $candidature = Candidature::with('annonce')->findOrFail($id);

        // Utiliser la Policy
        $this->authorize('updateStatut', $candidature);

        $request->validate([
            'statut' => ['required', Rule::in(['envoyee', 'en_cours', 'retenue', 'refusee'])],
        ]);

        $candidature->update(['statut' => $request->statut]);

        $candidature->load([
            'joueur.joueur.position',
            'joueur.joueur.nationality',
            'annonce'
        ]);

        return response()->json([
            'message' => 'Statut de la candidature mis à jour.',
            'data' => new CandidatureResource($candidature)
        ]);
    }

    /**
     * Annuler une candidature (joueur uniquement)
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $candidature = Candidature::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('delete', $candidature);

        $candidature->delete();

        return response()->json([
            'message' => 'Candidature annulée avec succès.'
        ]);
    }
}
