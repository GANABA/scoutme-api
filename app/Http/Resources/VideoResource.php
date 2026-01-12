<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'url_ou_fichier' => $this->url_ou_fichier,
            'uploaded_at' => $this->created_at?->format('Y-m-d'),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
