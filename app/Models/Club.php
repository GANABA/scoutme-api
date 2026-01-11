<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'nom',
        'country_id',
        'niveau',
        'site_web',
    ];

    /**
     * Relation : Pays du club
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relation : Joueurs actuellement dans ce club
     */
    public function joueurs()
    {
        return $this->hasMany(Joueur::class, 'club_actuel_id');
    }

    /**
     * Relation : Recruteurs associés à ce club
     */
    public function recruteurs()
    {
        return $this->hasMany(Recruteur::class);
    }

    /**
     * Relation : Expériences liées à ce club
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
}
