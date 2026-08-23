<?php

namespace App\Http\Controllers\Entraide;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use App\Models\Reponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReponseController extends Controller
{
    public function store(Request $request, Publication $question): RedirectResponse
    {
        $donnees = $request->validate([
            'contenu' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        Reponse::create([
            'publication_id' => $question->id,
            'user_id' => $request->user()->id,
            'contenu' => $donnees['contenu'],
        ]);

        return back()->with('succes', 'Votre réponse a été publiée.');
    }

    public function destroy(Reponse $reponse): RedirectResponse
    {
        $reponse->delete();

        return back()->with('succes', 'Réponse supprimée.');
    }
}