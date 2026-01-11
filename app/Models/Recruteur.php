<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recruteur extends Model
{
    protected $fillable = [
        'user_id',
        'nom_organisation',
        'country_id',
        'type',
        'logo_organisation',
        'club_id',
    ];

    /**
     * Relation : User parent
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Pays du recruteur
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relation : Club associé (si type = club)
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Relation : Joueurs en favoris
     */
    public function favoris()
    {
        return $this->belongsToMany(Joueur::class, 'favoris', 'recruteur_id', 'joueur_id')->withTimestamps();
    }

    /**
     * Relation : Annonces créées par ce recruteur
     */
    public function annonces()
    {
        return $this->hasMany(AnnonceRecrutement::class, 'recruteur_id');
    }
}
