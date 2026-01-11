<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AnnonceRecrutement extends Model
{
    protected $table = 'annonces_recrutement';

    protected $fillable = [
        'recruteur_id',
        'club_id',
        'titre',
        'description',
        'type',
        'position_id',
        'age_min',
        'age_max',
        'taille_min',
        'country_id',
        'date_limite',
        'statut',
        'visibilite',
    ];

    protected $casts = [
        'date_limite' => 'date',
    ];

    /**
     * Relation : Recruteur propriétaire de l'annonce
     */
    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }

    /**
     * Relation : Club associé (optionnel)
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Relation : Pays ciblé (optionnel)
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relation : Position recherchée (optionnel)
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Relation : Candidatures reçues pour cette annonce
     */
    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'annonce_id');
    }

    /**
     * Scope : Annonces publiées uniquement
     */
    public function scopePubliees(Builder $query): Builder
    {
        return $query->where('statut', 'publiee');
    }

    /**
     * Scope : Annonces publiques uniquement
     */
    public function scopePubliques(Builder $query): Builder
    {
        return $query->where('visibilite', 'publique');
    }

    /**
     * Scope : Annonces encore ouvertes (date limite non dépassée)
     */
    public function scopeOuvertes(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('date_limite')
              ->orWhere('date_limite', '>=', now());
        });
    }

    /**
     * Vérifier si l'annonce est encore ouverte
     */
    public function estOuverte(): bool
    {
        if ($this->statut !== 'publiee') {
            return false;
        }

        if ($this->date_limite === null) {
            return true;
        }

        return $this->date_limite->isFuture();
    }

    /**
     * Vérifier si un joueur a déjà postulé à cette annonce
     */
    public function aDejaPostule(int $joueurId): bool
    {
        return $this->candidatures()
            ->where('joueur_id', $joueurId)
            ->exists();
    }
}
