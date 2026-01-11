<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $fillable = [
        'joueur_id',
        'titre',
        'url_ou_fichier',
    ];

    /**
     * Cast des attributs
     */
    protected $casts = [
        // pas de cast particulier pour l'instant
    ];

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

}
