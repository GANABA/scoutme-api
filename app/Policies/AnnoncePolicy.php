<?php

namespace App\Policies;

use App\Models\AnnonceRecrutement;
use App\Models\User;

class AnnoncePolicy
{
    /**
     * Determine if the user can view the annonce (détails complets).
     * Les détails complets sont réservés au recruteur propriétaire.
     */
    public function viewPrivate(User $user, AnnonceRecrutement $annonce): bool
    {
        return $user->estRecruteur() && $user->id === $annonce->recruteur_id;
    }

    /**
     * Determine if the user can update the annonce.
     */
    public function update(User $user, AnnonceRecrutement $annonce): bool
    {
        return $user->estRecruteur() && $user->id === $annonce->recruteur_id;
    }

    /**
     * Determine if the user can delete the annonce.
     */
    public function delete(User $user, AnnonceRecrutement $annonce): bool
    {
        return $user->estRecruteur() && $user->id === $annonce->recruteur_id;
    }

    /**
     * Determine if the user can view candidatures for this annonce.
     */
    public function viewCandidatures(User $user, AnnonceRecrutement $annonce): bool
    {
        return $user->estRecruteur() && $user->id === $annonce->recruteur_id;
    }
}
