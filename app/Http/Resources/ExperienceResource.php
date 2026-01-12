<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'club' => new ClubResource($this->whenLoaded('club')),
            'type_organisation' => $this->type_organisation,
            'poste' => $this->poste,
            'date_debut' => $this->date_debut?->format('Y-m-d'),
            'date_fin' => $this->date_fin?->format('Y-m-d'),
            'commentaire' => $this->commentaire,
            'en_cours' => $this->date_fin === null,
        ];
    }
}
