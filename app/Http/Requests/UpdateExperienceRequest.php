<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Sécurisé par middleware 'joueur' + Policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => ['sometimes', 'integer', 'exists:clubs,id'],
            'type_organisation' => ['sometimes', Rule::in(['club', 'académie'])],
            'poste' => ['nullable', 'string', 'max:100'],
            'date_debut' => ['sometimes', 'date'],
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
            'club_id.exists' => 'Le club sélectionné n\'existe pas.',
            'type_organisation.in' => 'Le type doit être "club" ou "académie".',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ];
    }
}
