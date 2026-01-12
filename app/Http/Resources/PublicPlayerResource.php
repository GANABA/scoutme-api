<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPlayerResource extends JsonResource
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
                'error' => 'Profil joueur non trouvé'
            ];
        }

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            // Email masqué pour les non-authentifiés
            'photo_profil' => $joueur->photo_profil,

            // Informations sportives
            'position' => $this->whenLoaded('joueur.position',
                fn() => new PositionResource($joueur->position)
            ),
            'age' => $joueur->date_naissance ? $joueur->date_naissance->age : null,
            'taille' => $joueur->taille,
            'pied_fort' => $joueur->pied_fort,
            'nationality' => $this->whenLoaded('joueur.nationality',
                fn() => new CountryResource($joueur->nationality)
            ),

            // Club actuel
            'club_actuel' => $this->whenLoaded('joueur.clubActuel',
                fn() => $joueur->clubActuel ? new ClubResource($joueur->clubActuel) : null
            ),

            // Utilisation des counts de la query / compter les collections
            'nombre_videos' => $this->videos_count ?? ($joueur->nombre_videos ?? $joueur->videos->count()),
            'nombre_experiences' => $this->experiences_count ?? ($joueur->nombre_experiences ?? $joueur->experiences->count()),

            // Note : Les visiteurs ne voient pas les vidéos ni expériences détaillées
            // Ils doivent s'inscrire pour accéder à ces données
        ];
    }
}
