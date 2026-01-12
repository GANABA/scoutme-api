<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrivateAnnonceResource extends JsonResource
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
            'titre' => $this->titre,
            'description' => $this->description,
            'type' => $this->type,

            // Critères de recherche
            'position' => new PositionResource($this->whenLoaded('position')),
            'age_min' => $this->age_min,
            'age_max' => $this->age_max,
            'taille_min' => $this->taille_min,

            // Localisation
            'country' => new CountryResource($this->whenLoaded('country')),

            // Dates
            'date_limite' => $this->date_limite?->format('Y-m-d'),
            'est_ouverte' => $this->estOuverte(),

            // Statut et visibilité (info privée)
            'statut' => $this->statut,
            'visibilite' => $this->visibilite,

            // Recruteur complet
            'recruteur' => new RecruteurResource($this->whenLoaded('recruteur')),

            // Club associé
            'club' => new ClubResource($this->whenLoaded('club')),

            // Utiliser les counts chargés via withCount()
            'statistiques' => [
                'nombre_candidatures' => $this->candidatures_count ?? 0,
                'candidatures_envoyees' => $this->candidatures_envoyees_count ?? 0,
                'candidatures_en_cours' => $this->candidatures_en_cours_count ?? 0,
                'candidatures_retenues' => $this->candidatures_retenues_count ?? 0,
                'candidatures_refusees' => $this->candidatures_refusees_count ?? 0,
            ],

            // Candidatures détaillées (si demandées)
            'candidatures' => CandidatureResource::collection($this->whenLoaded('candidatures')),

            // Métadonnées
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
