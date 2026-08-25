<?php

namespace App\Http\Requests;

use App\Services\DoublonService;
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
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if (app(DoublonService::class)->estDoublon($this->input('contenu'), $this->user()->id)) {
                    $validator->errors()->add('contenu', 'Vous avez déjà publié un contenu très similaire récemment.');
                    return;
                }

                $texte = trim(($this->input('titre') ?? '') . ' ' . $this->input('contenu'));

                $resultat = app(ModerationService::class)->moderate($texte, $this->user()->id);

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