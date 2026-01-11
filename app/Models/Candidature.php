<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Candidature extends Model
{
    protected $fillable = [
        'annonce_id',
        'joueur_id',
        'message',
        'statut',
    ];

    /**
     * Relation : Annonce concernée
     */
    public function annonce()
    {
        return $this->belongsTo(AnnonceRecrutement::class, 'annonce_id');
    }

    /**
     * Relation : Joueur candidat
     */
    public function joueur()
    {
        return $this->belongsTo(User::class, 'joueur_id');
    }

    /**
     * Scope : Candidatures envoyées (en attente)
     */
    public function scopeEnvoyees(Builder $query): Builder
    {
        return $query->where('statut', 'envoyee');
    }

    /**
     * Scope : Candidatures en cours de traitement
     */
    public function scopeEnCours(Builder $query): Builder
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope : Candidatures retenues
     */
    public function scopeRetenues(Builder $query): Builder
    {
        return $query->where('statut', 'retenue');
    }

    /**
     * Scope : Candidatures refusées
     */
    public function scopeRefusees(Builder $query): Builder
    {
        return $query->where('statut', 'refusee');
    }

    /**
     * Vérifier si la candidature est encore en attente
     */
    public function estEnAttente(): bool
    {
        return $this->statut === 'envoyee';
    }

    /**
     * Marquer comme en cours
     */
    public function marquerEnCours(): bool
    {
        return $this->update(['statut' => 'en_cours']);
    }

    /**
     * Marquer comme retenue
     */
    public function retenir(): bool
    {
        return $this->update(['statut' => 'retenue']);
    }

    /**
     * Marquer comme refusée
     */
    public function refuser(): bool
    {
        return $this->update(['statut' => 'refusee']);
    }
}
