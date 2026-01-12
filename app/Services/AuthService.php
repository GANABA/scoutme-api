<?php

namespace App\Services;

use App\Models\Joueur;
use App\Models\Recruteur;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Inscription d'un nouvel utilisateur avec son profil (joueur ou recruteur)
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            // Création de l'utilisateur
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'role'       => $data['role'],
            ]);

            // Création du profil selon le rôle
            if ($data['role'] === 'joueur') {
                Joueur::create([
                    'user_id'        => $user->id,
                    'position_id'    => $data['position_id'],
                    'date_naissance' => $data['date_naissance'],
                    'taille'         => $data['taille'],
                    'pied_fort'      => $data['pied_fort'],
                    'nationality_id' => $data['nationality_id'],
                ]);

                $user->load([
                    'joueur.position',
                    'joueur.nationality',
                ]);
            } elseif ($data['role'] === 'recruteur') {
                Recruteur::create([
                    'user_id'          => $user->id,
                    'nom_organisation' => $data['nom_organisation'],
                    'country_id'       => $data['country_id'],
                    'type'             => $data['type'],
                ]);

                $user->load([
                    'recruteur.country',
                ]);
            }

            // Génération du token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        });
    }

    /**
     * Connexion d'un utilisateur existant
     *
     * @param string $email
     * @param string $password
     * @return array
     * @throws ValidationException
     */
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        // Vérification des credentials
        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        // Chargement des relations selon le rôle
        if ($user->estJoueur()) {
            $user->load([
                'joueur.position',
                'joueur.nationality',
                'joueur.clubActuel.country',
            ]);
        } elseif ($user->estRecruteur()) {
            $user->load([
                'recruteur.country',
                'recruteur.club',
            ]);
        }

        // Génération du token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Déconnexion d'un utilisateur (suppression du token actuel)
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        optional($user->currentAccessToken())->delete();
    }
}
