<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'nom_fr',
        'nom_en',
        'abreviation',
    ];

    /**
     * Relation inverse : Joueurs ayant cette position
     */
    public function joueurs()
    {
        return $this->hasMany(Joueur::class, 'position_id');
    }
}
