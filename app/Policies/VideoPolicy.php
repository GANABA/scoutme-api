<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy
{
    /**
     * Determine if the user can view the video.
     */
    public function view(User $user, Video $video): bool
    {
        // Seul le propriétaire (joueur) peut voir sa vidéo
        return $user->estJoueur() && $user->joueur->id === $video->joueur_id;
    }

    /**
     * Determine if the user can update the video.
     */
    public function update(User $user, Video $video): bool
    {
        return $user->estJoueur() && $user->joueur->id === $video->joueur_id;
    }

    /**
     * Determine if the user can delete the video.
     */
    public function delete(User $user, Video $video): bool
    {
        return $user->estJoueur() && $user->joueur->id === $video->joueur_id;
    }
}
