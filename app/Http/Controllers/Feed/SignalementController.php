<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\Publication;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    use AuthorizesRequests;

    protected const MOTIFS = ['spam', 'hors_sujet', 'inapproprie', 'harcelement', 'autre'];

    public function store(Request $request, Publication $publication): RedirectResponse
    {
        $this->authorize('signaler', $publication);

        $donnees = $request->validate([
            'motif' => ['required', 'string', 'in:' . implode(',', self::MOTIFS)],
        ]);

        $dejaSignale = $publication->signalements()
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($dejaSignale) {
            return back()->with('erreur', 'Vous avez déjà signalé cette publication.');
        }

        $publication->signalements()->create([
            'user_id' => $request->user()->id,
            'motif' => $donnees['motif'],
        ]);

        $seuil = config('cohorte.seuil_signalement');

        if ($publication->signalements()->count() >= $seuil && $publication->statut === 'publie') {
            $publication->update(['statut' => 'masque']);
        }

        return back()->with('succes', 'Merci, votre signalement a été enregistré.');
    }
}