<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joueur extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'date_naissance',
        'taille',
        'pied_fort',
        'nationalite',
        'club_actuel_id',
        'motivation',
        'photo_profil',
    ];


}
