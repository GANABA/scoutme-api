<?php

namespace App\Policies;

use App\Models\Candidature;
use App\Models\User;

class CandidaturePolicy
{
    /**
     * Determine if the user can delete (annuler) the candidature.
     * Seul le joueur propriétaire peut annuler sa candidature si elle est encore en attente.
     */
    public function delete(User $user, Candidature $candidature): bool
    {
        return $user->estJoueur()
            && $user->id === $candidature->joueur_id
            && $candidature->estEnAttente();
    }

    /**
     * Determine if the user can update the status of the candidature.
     * Seul le recruteur propriétaire de l'annonce peut changer le statut.
     */
    public function updateStatut(User $user, Candidature $candidature): bool
    {
        $candidature->load('annonce');

        return $user->estRecruteur()
            && $user->id === $candidature->annonce->recruteur_id;
    }
}
