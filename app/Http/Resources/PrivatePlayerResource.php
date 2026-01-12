<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrivatePlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // S'assurer que la relation joueur est chargée
        if (!$this->relationLoaded('joueur')) {
            $this->load('joueur');
        }

        $joueur = $this->joueur;

        // Si pas de profil joueur, retourner données minimales
        if (!$joueur) {
            return [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'role' => $this->role,
                'error' => 'Profil joueur non trouvé'
            ];
        }

        return [
            // Données User complètes
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'photo_profil' => $joueur->photo_profil,

            // Informations sportives complètes
            'position' => $this->whenLoaded('joueur.position',
                fn() => new PositionResource($joueur->position)
            ),
            'date_naissance' => $joueur->date_naissance?->format('Y-m-d'),
            'age' => $joueur->date_naissance ? $joueur->date_naissance->age : null,
            'taille' => $joueur->taille,
            'pied_fort' => $joueur->pied_fort,
            'nationality' => $this->whenLoaded('joueur.nationality',
                fn() => new CountryResource($joueur->nationality)
            ),
            'motivation' => $joueur->motivation,

            // Club actuel
            'club_actuel' => $this->whenLoaded('joueur.clubActuel',
                fn() => $joueur->clubActuel ? new ClubResource($joueur->clubActuel) : null
            ),

            // Si la relation est chargée : collection complète
            // Sinon : tableau vide
            'videos' => $joueur->relationLoaded('videos')
                ? VideoResource::collection($joueur->videos)
                : [],

            'experiences' => $joueur->relationLoaded('experiences')
                ? ExperienceResource::collection($joueur->experiences)
                : [],

            // Métadonnées
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
