<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Sécurisé par middleware 'joueur'
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => ['required', 'integer', 'exists:clubs,id'],
            'type_organisation' => ['required', Rule::in(['club', 'académie'])],
            'poste' => ['nullable', 'string', 'max:100'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'commentaire' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Messages d'erreur personnalisés
     */
    public function messages(): array
    {
        return [
            'club_id.required' => 'Le club est requis.',
            'club_id.exists' => 'Le club sélectionné n\'existe pas.',
            'type_organisation.required' => 'Le type d\'organisation est requis.',
            'type_organisation.in' => 'Le type doit être "club" ou "académie".',
            'date_debut.required' => 'La date de début est requise.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ];
    }
}
