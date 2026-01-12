<?php

namespace App\Http\Controllers\Joueur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Liste des vidéos du joueur connecté
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

        $videos = $user->joueur->videos()->latest()->get();

        return response()->json([
            'data' => VideoResource::collection($videos)
        ]);
    }

    /**
     * Ajouter une vidéo
     */
    public function store(StoreVideoRequest $request): JsonResponse
    {
        $user = $request->user();

        $video = Video::create([
            'joueur_id' => $user->joueur->id,
            ...$request->validated()
        ]);

        return response()->json([
            'message' => 'Vidéo ajoutée avec succès.',
            'data' => new VideoResource($video)
        ], 201);
    }

    /**
     * Afficher une vidéo spécifique
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('view', $video);

        return response()->json([
            'data' => new VideoResource($video)
        ]);
    }

    /**
     * Mettre à jour une vidéo
     */
    public function update(UpdateVideoRequest $request, int $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('update', $video);

        $video->update($request->validated());

        return response()->json([
            'message' => 'Vidéo mise à jour avec succès.',
            'data' => new VideoResource($video)
        ]);
    }

    /**
     * Supprimer une vidéo
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $video = Video::findOrFail($id);

        // Utiliser la Policy
        $this->authorize('delete', $video);

        $video->delete();

        return response()->json([
            'message' => 'Vidéo supprimée avec succès.'
        ]);
    }
}
