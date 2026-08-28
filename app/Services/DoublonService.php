<?php

namespace App\Services;

use App\Models\Publication;

class DoublonService
{
    /**
     * Vérifie si $contenu ressemble trop à une publication récente du même auteur.
     */
    public function estDoublon(string $contenu, int $userId): bool
    {
        $seuil = config('cohorte.seuil_similarite_doublon');
        $normalise = $this->normaliser($contenu);

        $recentes = Publication::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDay())
            ->pluck('contenu');

        foreach ($recentes as $ancienContenu) {
            similar_text($normalise, $this->normaliser($ancienContenu), $pourcentage);

            if ($pourcentage >= $seuil) {
                return true;
            }
        }

        return false;
    }

    protected function normaliser(string $texte): string
    {
        $texte = mb_strtolower($texte);
        $texte = preg_replace('/[[:punct:]]+/u', '', $texte);
        $texte = preg_replace('/\s+/', ' ', $texte);

        return trim($texte);
    }
}