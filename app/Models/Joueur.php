<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Joueur extends Model
{
    protected $fillable = [
        'user_id',
        'position_id',
        'date_naissance',
        'taille',
        'pied_fort',
        'nationality_id',
        'club_actuel_id',
        'motivation',
        'photo_profil',
    ];

    /**
     * Cast des attributs
     */
    protected $casts = [
        'date_naissance' => 'date',
    ];

    /**
     * Relation : User parent
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Position du joueur
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Relation : Nationalité (Country)
     */
    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    /**
     * Relation : Club actuel
     */
    public function clubActuel()
    {
        return $this->belongsTo(Club::class, 'club_actuel_id');
    }

    /**
     * Relation : Expériences du joueur
     */
    public function experiences()
    {
        return $this->hasMany(Experience::class)->orderBy('date_debut', 'desc');
    }

    /**
     * Relation : Vidéos du joueur
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Relation : Recruteurs ayant mis ce joueur en favoris
     */
    public function favorisPar()
    {
        return $this->belongsToMany(Recruteur::class, 'favoris', 'joueur_id', 'recruteur_id')->withTimestamps();
    }

    /**
     * Relation : Candidatures envoyées par ce joueur
     */
    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'joueur_id');
    }
}
