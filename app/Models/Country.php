<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name_fr',
        'name_en',
        'iso2',
        'iso3',
        'phone_code',
    ];

    /**
     * Relation inverse : Joueurs ayant cette nationalité
     */
    public function joueurs()
    {
        return $this->hasMany(Joueur::class, 'nationality_id');
    }

    /**
     * Relation inverse : Recruteurs dans ce pays
     */
    public function recruteurs()
    {
        return $this->hasMany(Recruteur::class, 'country_id');
    }

    /**
     * Relation inverse : Clubs dans ce pays
     */
    public function clubs()
    {
        return $this->hasMany(Club::class, 'country_id');
    }

    /**
     * Relation inverse : Annonces ciblant ce pays
     */
    public function annonces()
    {
        return $this->hasMany(AnnonceRecrutement::class, 'country_id');
    }
}
