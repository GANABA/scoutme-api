<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnonceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Sécurisé par middleware 'recruteur'
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => ['nullable', 'integer', 'exists:clubs,id'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', Rule::in(['recrutement', 'selection', 'test'])],
            'position_id' => ['nullable', 'integer', 'exists:positions,id'],
            'age_min' => ['nullable', 'integer', 'min:10', 'max:50'],
            'age_max' => ['nullable', 'integer', 'min:10', 'max:50', 'gte:age_min'],
            'taille_min' => ['nullable', 'integer', 'min:100', 'max:250'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'date_limite' => ['nullable', 'date', 'after_or_equal:today'],
            'statut' => ['sometimes', Rule::in(['brouillon', 'publiee', 'fermee'])],
            'visibilite' => ['sometimes', Rule::in(['publique', 'privee'])],
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre de l\'annonce est requis.',
            'description.required' => 'La description est requise.',
            'type.required' => 'Le type d\'annonce est requis.',
            'position_id.exists' => 'La position sélectionnée n\'existe pas.',
            'age_max.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum.',
            'date_limite.after_or_equal' => 'La date limite doit être aujourd\'hui ou dans le futur.',
            'country_id.exists' => 'Le pays sélectionné n\'existe pas.',
        ];
    }
}
