<?php

namespace App\Http\Controllers\Entraide;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Reponse;
use App\Services\ModerationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReponseController extends Controller
{
    public function store(Request $request, Publication $question): RedirectResponse
    {
        $donnees = $request->validate([
            'contenu' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $resultat = app(ModerationService::class)->moderate($donnees['contenu'], $request->user()->id);

        if (! $resultat['appropriate']) {
            throw ValidationException::withMessages([
                'contenu' => 'Votre réponse a été refusée par la modération automatique'
                    . ($resultat['reason'] ? " : {$resultat['reason']}" : '.'),
            ]);
        }

        Reponse::create([
            'publication_id' => $question->id,
            'user_id' => $request->user()->id,
            'contenu' => $donnees['contenu'],
        ]);

        $request->user()->increment('points', 3);

        return back()->with('succes', 'Votre réponse a été publiée.');
    }

    public function destroy(Reponse $reponse): RedirectResponse
    {
        $reponse->delete();

        return back()->with('succes', 'Réponse supprimée.');
    }
}