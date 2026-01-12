<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecruteurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $recruteur = $this->recruteur;

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,

            // Informations organisation
            'nom_organisation' => $recruteur?->nom_organisation,
            'country' => new CountryResource($this->whenLoaded('recruteur.country')),
            'type' => $recruteur?->type,
            'logo_organisation' => $recruteur?->logo_organisation,

            // Club associé (si type = club)
            'club' => new ClubResource($this->whenLoaded('recruteur.club')),

            // Métadonnées
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
