<?php

namespace App\Http\Requests;

use App\Services\ModerationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->promotion_id !== null;
    }

    public function rules(): array
    {
        return [
            'titre' => ['nullable', 'string', 'max:150'],
            'contenu' => ['required', 'string', 'min:10', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'contenu.required' => 'Votre publication ne peut pas être vide.',
            'contenu.min' => 'Votre publication doit faire au moins 10 caractères.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // On ne lance la modération que si les règles de base sont déjà valides
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $texte = trim(($this->input('titre') ?? '') . ' ' . $this->input('contenu'));

                $resultat = app(ModerationService::class)->moderate($texte);

                if (! $resultat['appropriate']) {
                    $validator->errors()->add(
                        'contenu',
                        'Votre publication a été refusée par la modération automatique'
                        . ($resultat['reason'] ? " : {$resultat['reason']}" : '.')
                    );
                }
            },
        ];
    }
}