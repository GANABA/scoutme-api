<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Sécurisé par middleware 'joueur'
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Champs User
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],

            // Champs Joueur
            'position_id' => ['sometimes', 'integer', 'exists:positions,id'],
            'date_naissance' => [
                'sometimes',
                'date',
                'before:today',
                'after:' . now()->subYears(100)->format('Y-m-d'), // Max 100 ans
                'before:' . now()->subYears(12)->format('Y-m-d'), // Min 12 ans
            ],
            'taille' => ['sometimes', 'integer', 'min:100', 'max:250'],
            'pied_fort' => ['sometimes', Rule::in(['Droit', 'Gauche', 'Ambidextre'])],
            'nationality_id' => ['sometimes', 'integer', 'exists:countries,id'],
            'club_actuel_id' => ['nullable', 'integer', 'exists:clubs,id'],
            'motivation' => ['nullable', 'string', 'max:1000'],
            'photo_profil' => ['nullable', 'string', 'max:500'], // Chemin ou URL
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'date_naissance.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'date_naissance.after' => 'L\'âge maximum est de 100 ans.',
            'position_id.exists' => 'La position sélectionnée n\'existe pas.',
            'nationality_id.exists' => 'La nationalité sélectionnée n\'existe pas.',
            'club_actuel_id.exists' => 'Le club sélectionné n\'existe pas.',
            'taille.min' => 'La taille minimale est de 100 cm.',
            'taille.max' => 'La taille maximale est de 250 cm.',
        ];
    }
}
