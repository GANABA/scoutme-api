<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Accessible à tous (inscription publique)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Champs communs à tous les utilisateurs
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'   => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Au moins une majuscule
                'regex:/[a-z]/',      // Au moins une minuscule
                'regex:/[0-9]/',      // Au moins un chiffre
            ],
            'role' => ['required', Rule::in(['joueur', 'recruteur'])],

            // Champs spécifiques au joueur (requis seulement si role == joueur)
            'position_id'       => ['required_if:role,joueur', 'integer', 'exists:positions,id'],
            'date_naissance'    => ['required_if:role,joueur', 'date', 'before:today'],
            'taille'            => ['required_if:role,joueur', 'integer', 'min:100', 'max:250'],
            'pied_fort'         => ['required_if:role,joueur', Rule::in(['Droit', 'Gauche', 'Ambidextre'])],
            'nationality_id'    => ['required_if:role,joueur', 'integer', 'exists:countries,id'],

            // Champs spécifiques au recruteur (requis seulement si role == recruteur)
            'nom_organisation' => ['required_if:role,recruteur', 'string', 'max:255'],
            'country_id'       => ['required_if:role,recruteur', 'integer', 'exists:countries,id'],
            'type'             => ['required_if:role,recruteur', Rule::in(['club', 'agent', 'scout'])],
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre.',
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'position_id.exists' => 'La position sélectionnée n\'existe pas.',
            'nationality_id.exists' => 'La nationalité sélectionnée n\'existe pas.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
        ];
    }
}
