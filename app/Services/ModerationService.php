<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModerationService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key');
        $this->model = config('services.openrouter.model');
    }

    /**
     * Analyse un texte pour un utilisateur donné et retourne :
     * ['appropriate' => bool, 'reason' => string|null]
     */
    public function moderate(string $content, ?int $userId = null): array
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenRouter: clé API absente, modération ignorée.');
            return ['appropriate' => true, 'reason' => null];
        }

        if ($userId !== null && $this->quotaDepasse($userId)) {
            return $this->comportementParDefaut('Quota quotidien de modération IA atteint.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Tu es un modérateur de contenu pour un réseau social étudiant. '
                            . 'Analyse le texte fourni et détermine s\'il contient des insultes, du harcèlement, '
                            . 'du discours haineux, du spam ou du contenu sexuel explicite. '
                            . 'Réponds UNIQUEMENT avec un objet JSON valide, sans texte autour, au format exact : '
                            . '{"appropriate": true ou false, "reason": "raison courte en français ou null"}',
                    ],
                    [
                        'role' => 'user',
                        'content' => $content,
                    ],
                ],
                'temperature' => 0,
            ]);

            if ($userId !== null) {
                $this->incrementerQuota($userId);
            }

            if (! $response->successful()) {
                Log::error('OpenRouter: erreur API', ['status' => $response->status(), 'body' => $response->body()]);
                return $this->comportementParDefaut('Erreur du service de modération.');
            }

            $raw = $response->json('choices.0.message.content');
            $parsed = json_decode($raw, true);

            if (! is_array($parsed) || ! array_key_exists('appropriate', $parsed)) {
                Log::error('OpenRouter: réponse mal formée', ['raw' => $raw]);
                return $this->comportementParDefaut('Réponse de modération illisible.');
            }

            return [
                'appropriate' => (bool) $parsed['appropriate'],
                'reason' => $parsed['reason'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('OpenRouter: exception', ['message' => $e->getMessage()]);
            return $this->comportementParDefaut('Service de modération indisponible.');
        }
    }

    protected function comportementParDefaut(string $raisonSiRefuse): array
    {
        if (config('cohorte.moderation_fail_open')) {
            return ['appropriate' => true, 'reason' => null];
        }

        return ['appropriate' => false, 'reason' => $raisonSiRefuse];
    }

    protected function quotaDepasse(int $userId): bool
    {
        $cle = $this->cleQuota($userId);
        $utilises = (int) Cache::get($cle, 0);

        return $utilises >= config('cohorte.quota_ia_quotidien');
    }

    protected function incrementerQuota(int $userId): void
    {
        $cle = $this->cleQuota($userId);
        $expiration = now()->endOfDay();

        Cache::add($cle, 0, $expiration);
        Cache::increment($cle);
    }

    protected function cleQuota(int $userId): string
    {
        return 'moderation_ia_quota:' . $userId . ':' . now()->toDateString();
    }
}