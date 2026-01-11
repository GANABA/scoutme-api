<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    /**
     * Relation : Profil joueur (si role = joueur)
     */
    public function joueur()
    {
        return $this->hasOne(Joueur::class);
    }

    /**
     * Relation : Profil recruteur (si role = recruteur)
     */
    public function recruteur()
    {
        return $this->hasOne(Recruteur::class);
    }

    /**
     * Relation : Messages envoyés
     */
    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'expediteur_id');
    }

    /**
     * Relation : Messages reçus
     */
    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'destinataire_id');
    }

    /**
     * Relation : Demandes d'interaction émises
     */
    public function demandesEmises()
    {
        return $this->hasMany(DemandeInteraction::class, 'emetteur_id');
    }

    /**
     * Relation : Demandes d'interaction reçues
     */
    public function demandesRecues()
    {
        return $this->hasMany(DemandeInteraction::class, 'recepteur_id');
    }

    /**
     * Relation : Annonces créées (si recruteur)
     */
    public function annonces()
    {
        return $this->hasMany(AnnonceRecrutement::class, 'recruteur_id');
    }

    /**
     * Relation : Candidatures envoyées (si joueur)
     */
    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'joueur_id');
    }

    /**
     * Vérifier si l'utilisateur est un joueur
     */
    public function estJoueur(): bool
    {
        return $this->role === 'joueur';
    }

    /**
     * Vérifier si l'utilisateur est un recruteur
     */
    public function estRecruteur(): bool
    {
        return $this->role === 'recruteur';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
