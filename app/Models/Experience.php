<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    //
    protected $fillable = [
        'joueur_id',
        'club_id',
        'type_organisation',
        'poste',
        'date_debut',
        'date_fin',
        'commentaire',
    ];

    /**
     * Cast des attributs
     */
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function joueur()
    {
        return $this->belongsTo(Joueur::class);
    }

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

}
