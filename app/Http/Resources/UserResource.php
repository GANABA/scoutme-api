<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource générique pour un utilisateur
 * Retourne le profil approprié selon le rôle
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Déterminer si c'est une vue privée (utilisateur connecté voit son propre profil)
        $isOwner = $request->user()?->id === $this->id;

        // Retourner la resource appropriée selon le rôle
        if ($this->role === 'joueur') {
            $data = $isOwner || $request->user()?->estRecruteur()
                ? (new PrivatePlayerResource($this))->toArray($request)
                : (new PublicPlayerResource($this))->toArray($request);

            // S'assurer que le rôle est bien 'joueur' dans la réponse
            $data['role'] = 'joueur';

            return $data;
        }

        if ($this->role === 'recruteur') {
            return (new RecruteurResource($this))->toArray($request);
        }

        // Fallback : données de base uniquement
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->when($isOwner, $this->email),
            'role' => $this->role,
        ];
    }
}
