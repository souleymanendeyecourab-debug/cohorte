   (Alternative à `php artisan serve`, non fonctionnel dans certains environnements Windows.)

8. Accéder à l'application sur [http://127.0.0.1:9999](http://127.0.0.1:9999)

## Comptes de démonstration

Voir `database/seeders/DatabaseSeeder.php` pour la liste complète des comptes créés automatiquement (étudiants et enseignant).

## Configuration métier

Les réglages spécifiques au projet sont centralisés dans `config/cohorte.php` :

| Clé | Description | Valeur par défaut |
|---|---|---|
| `quota_ia_quotidien` | Nombre d'appels à la modération IA autorisés par membre et par jour | 10 |
| `seuil_signalement` | Nombre de signalements avant masquage automatique d'une publication | 3 |
| `seuil_epinglage` | Score de réputation ouvrant le droit d'épingler ses publications | 50 |
| `seuil_similarite_doublon` | Pourcentage de similarité à partir duquel deux publications du même auteur sont jugées doublons | 92 |
| `moderation_fail_open` | Comportement si la modération IA est indisponible : laisser passer (`true`) ou refuser (`false`) | `false` |

## Documentation complémentaire

- [`docs/JOURNAL.md`](docs/JOURNAL.md) — journal de bord détaillant chaque phase de développement
- [`docs/decisions.md`](docs/decisions.md) — justification des choix techniques structurants
## Structure Git

Le projet suit un flux par branches de fonctionnalité (`feat/NN-nom-de-la-phase`), fusionnées dans `main` et taguées (`phase-NN`) à chaque étape validée.