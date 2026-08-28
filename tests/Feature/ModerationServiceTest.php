<?php

namespace Tests\Feature;

use App\Services\ModerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ModerationServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_contenu_approprie_est_accepte(): void
    {
        Http::fake([
            'openrouter.ai/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode(['appropriate' => true, 'reason' => null])]],
                ],
            ], 200),
        ]);

        $resultat = app(ModerationService::class)->moderate('Bonjour à tous, quelqu\'un a compris l\'exercice ?');

        $this->assertTrue($resultat['appropriate']);
        $this->assertNull($resultat['reason']);
    }

    public function test_contenu_inapproprie_est_refuse(): void
    {
        Http::fake([
            'openrouter.ai/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode(['appropriate' => false, 'reason' => 'Contient des insultes'])]],
                ],
            ], 200),
        ]);

        $resultat = app(ModerationService::class)->moderate('tu es vraiment con et inutile');

        $this->assertFalse($resultat['appropriate']);
        $this->assertSame('Contient des insultes', $resultat['reason']);
    }

    public function test_erreur_api_declenche_le_comportement_fail_closed(): void
    {
        config(['cohorte.moderation_fail_open' => false]);

        Http::fake([
            'openrouter.ai/*' => Http::response(['error' => ['message' => 'Server error']], 500),
        ]);

        $resultat = app(ModerationService::class)->moderate('un texte quelconque');

        $this->assertFalse($resultat['appropriate']);
    }

    public function test_erreur_api_declenche_le_comportement_fail_open_si_configure(): void
    {
        config(['cohorte.moderation_fail_open' => true]);

        Http::fake([
            'openrouter.ai/*' => Http::response(['error' => ['message' => 'Server error']], 500),
        ]);

        $resultat = app(ModerationService::class)->moderate('un texte quelconque');

        $this->assertTrue($resultat['appropriate']);
    }

    public function test_reponse_json_illisible_declenche_le_comportement_par_defaut(): void
    {
        config(['cohorte.moderation_fail_open' => false]);

        Http::fake([
            'openrouter.ai/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => 'ceci n\'est pas du JSON valide']],
                ],
            ], 200),
        ]);

        $resultat = app(ModerationService::class)->moderate('un texte quelconque');

        $this->assertFalse($resultat['appropriate']);
    }

    public function test_quota_depasse_declenche_le_comportement_par_defaut_sans_appel_api(): void
    {
        config(['cohorte.moderation_fail_open' => false, 'cohorte.quota_ia_quotidien' => 1]);

        Http::fake([
            'openrouter.ai/*' => Http::response([
                'choices' => [
                    ['message' => ['content' => json_encode(['appropriate' => true, 'reason' => null])]],
                ],
            ], 200),
        ]);

        $userId = 999;

        // Premier appel : consomme le quota
        app(ModerationService::class)->moderate('premier message', $userId);

        // Deuxième appel : quota épuisé, doit être refusé sans appeler l'API
        $resultat = app(ModerationService::class)->moderate('deuxieme message', $userId);

        $this->assertFalse($resultat['appropriate']);
        Http::assertSentCount(1); // un seul appel API a été fait, pas deux
    }
}