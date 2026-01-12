<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Annonce concernée
            'annonce' => new PublicAnnonceResource($this->whenLoaded('annonce')),

            // Joueur candidat (profil adapté selon le contexte)
            'joueur' => $this->when(
                $request->user()?->estRecruteur(),
                new PrivatePlayerResource($this->whenLoaded('joueur')),
                new PublicPlayerResource($this->whenLoaded('joueur'))
            ),

            // Détails de la candidature
            'message' => $this->message,
            'statut' => $this->statut,
            'est_en_attente' => $this->estEnAttente(),

            // Métadonnées
            'date_candidature' => $this->created_at?->format('Y-m-d H:i'),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
