<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicAnnonceResource extends JsonResource
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

            // Informations recruteur (limitées)
            'recruteur' => $this->when(
                $this->relationLoaded('recruteur'),
                fn() => [
                    'nom_organisation' => $this->recruteur?->recruteur?->nom_organisation,
                    'type' => $this->recruteur?->recruteur?->type,
                ]
            ),

            // Club associé
            'club' => new ClubResource($this->whenLoaded('club')),

            // Métadonnées
            'publiee_le' => $this->created_at?->format('Y-m-d'),
        ];
    }
}
