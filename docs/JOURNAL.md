## Phase 0 — Installation et mise en place du dépôt

Branche : feat/00-installation
Date : [mets la date d'aujourd'hui]

### Ce que j'ai fait
Installation de Laravel 12.66 via Herd (PHP 8.4), configuration de SQLite comme base de données, création du dépôt Git et connexion au dépôt distant GitHub. Ajout de la configuration métier centralisée dans config/cohorte.php et du gabarit de base avec le composant d'alerte.

### Pourquoi je l'ai fait ainsi
SQLite évite d'installer un serveur de base de données séparé. La configuration centralisée dans un seul fichier permet de changer les seuils métier sans toucher au code.

### La difficulté rencontrée
Conflit entre les versions PHP installées (XAMPP en 8.1 vs Herd en 8.4) qui bloquait l'installation de Laravel 12, car il nécessite PHP 8.2 minimum.

### Comment je l'ai résolue
Suppression de C:\xampp\php du PATH des variables système Windows pour laisser Herd prioritaire.


## Phase 1 — Le modèle de données et les relations Eloquent

Branche : feat/01-modele-donnees
Dates : 13 août 2026

### Ce que j'ai fait
J'ai créé les migrations pour promotions, publications, reponses, 
signalements, appels_ia, et ajouté promotion_id/role/points sur users. 
Après j'ai créé les modèles, et dans chaque modèle j'ai ajouté des 
méthodes qui permettent de récupérer facilement les données liées. 
J'ai testé avec migrate:fresh et Tinker pour voir si ça marchait.

### Pourquoi je l'ai fait ainsi
J'ai suivi l'ordre du guide pour les migrations, sinon ça bloque à cause 
des clés étrangères. C'est comme ça qu'on a la liaison des tables.

### La difficulté rencontrée
J'ai eu du mal avec l'ordre des migrations. Je pensais que ça n'avait pas 
d'importance mais l'ordre est important car les tables sont dépendantes 
les unes des autres.

### Comment je l'ai résolue
J'ai regardé l'ordre des fichiers et suivi ce que dit le guide : créer 
reponse_retenue_id après la table reponses, dans une migration à part. 
J'ai aussi utilisé l'IA pour comprendre comment écrire ces méthodes 
(belongsTo et hasMany), je l'avais pas trop compris au début.


## Phase 2 — Les factories et les seeders

Branche : feat/02-seeders
Dates : 17 août 2026

### Ce que j'ai fait
J'ai rempli les factories pour Promotion, Publication et Reponse, puis 
j'ai écrit le seeder principal qui crée deux promotions avec leurs 
membres, des publications, des questions/réponses, et les quatre 
comptes de démonstration. J'ai testé avec migrate:fresh --seed et 
vérifié les chiffres dans Tinker.

### Pourquoi je l'ai fait ainsi
J'ai suivi le guide pour avoir deux promotions bien séparées, ce qui 
sert plus tard à tester le cloisonnement entre groupes.

### La difficulté rencontrée
J'ai eu un problème de branche : j'ai fait mes commits directement sur 
main au lieu de feat/02-seeders sans m'en rendre compte. J'ai aussi eu 
du mal à comprendre recycle() dans les factories.

### Comment je l'ai résolue
J'ai créé la branche feat/02-seeders après coup à partir de mon travail 
sur main, et je l'ai poussée sur GitHub. Pour recycle(), j'ai compris 
que ça sert à réutiliser les membres déjà créés au lieu d'en fabriquer 
des nouveaux à chaque publication.



## Phase 3 — L'authentification avec Laravel Fortify

Branche : feat/03-authentification-fortify
Dates : 17 au 19 août 2026

### Ce que j'ai fait
J'ai installé Fortify, configuré les fonctionnalités utiles (inscription, 
réinitialisation du mot de passe) et retiré celles hors périmètre (2FA, 
passkeys). J'ai écrit les 4 vues d'authentification (login, register, 
forgot-password, reset-password), le layout de base, et modifié 
CreateNewUser.php pour vérifier le code d'invitation à l'inscription. 
J'ai testé à la main : inscription avec code valide, inscription avec 
code invalide, connexion.

### Pourquoi je l'ai fait ainsi
Fortify gère la sécurité (mots de passe, tentatives de connexion) mais 
ne fournit aucune vue, donc c'est à moi d'écrire les formulaires. Ça 
permet de comprendre ce qui se passe au lieu d'utiliser un truc tout fait.

### La difficulté rencontrée
J'ai eu deux problèmes. D'abord php artisan serve refusait de démarrer 
sur tous les ports, j'ai dû utiliser php -S à la place. Ensuite le 
layout plantait sur toutes les pages à cause d'un lien vers une route 
(feed.index) qui n'existe pas encore, donc même la page d'inscription 
ne s'affichait pas.

### Comment je l'ai résolue
Pour le serveur, j'ai utilisé la commande php -S 127.0.0.1:8000 -t public 
à la place de php artisan serve. Pour le layout, j'ai utilisé 
Route::has() pour vérifier si la route existe avant de générer son URL, 
et rediriger vers / sinon.


## Phase 4 — Rejoindre une promotion et le profil

Branche : feat/04-adhesion-promotion
Dates : 20 au 21 août 2026

### Ce que j'ai fait
Créé le middleware ExigePromotion pour rediriger les membres sans promotion vers la page
d'adhésion, le AdhesionController permettant de rejoindre une promotion via un code
d'invitation, et le ProfilController + la vue associée pour afficher les informations du
membre connecté (nom, e-mail, rôle, promotion, points).

### Pourquoi je l'ai fait ainsi
J'ai suivi la structure imposée par le guide : un middleware dédié pour garantir qu'aucune
route métier ne reçoive un utilisateur sans promotion_id, et des contrôleurs organisés par
module (Promotion/, Profil/) plutôt qu'à plat.

### La difficulté rencontrée
J'ai eu deux problèmes distincts. D'abord, un bug de syntaxe Blade dans profil/show.blade.php
(la directive @extends mal écrite et des caractères parasites en fin de fichier), qui empêchait
la vue de se compiler. Ensuite, un problème d'environnement bien plus long à diagnostiquer :
`php artisan serve` refusait de démarrer sur tous les ports testés, et même après avoir
contourné ça avec `php -S`, mon navigateur Chrome semblait bloquer l'affichage des pages
malgré des routes fonctionnelles.

### Comment je l'ai résolue
Pour le bug Blade, j'ai comparé mon fichier ligne par ligne avec la syntaxe attendue.
Pour le problème d'environnement, j'ai isolé la cause étape par étape : d'abord confirmé que
PHP fonctionnait bien avec `php -S`, puis confirmé avec `curl.exe` que le serveur et Laravel
répondaient correctement même quand le navigateur semblait ne rien afficher — ce qui a
prouvé que le problème venait du navigateur et non de mon code. Un second profil Chrome a
résolu le blocage.

## Phase 5 — Le fil de promotion et le cloisonnement

Branche : feat/05-fil-promotion
Dates : 22 au 23 août 2026

### Ce que j'ai fait
Créé le contrôleur de ressource PublicationController (index, create, store, show, destroy),
la PublicationPolicy qui garantit le cloisonnement entre promotions, le StorePublicationRequest
pour la validation, le composant Blade carte-publication, et les vues du fil (index, create,
show). Testé la création de publication et surtout le cloisonnement : un membre d'une
promotion ne peut pas accéder à une publication d'une autre promotion.

### Pourquoi je l'ai fait ainsi
J'ai utilisé $this->authorize() explicitement dans chaque méthode du contrôleur plutôt que
authorizeResource() dans le constructeur, comme recommandé au départ. La policy protège
l'accès direct par URL (via view, create, delete), tandis que le scope deLaPromotion() dans
index() filtre la liste affichée. Les deux mécanismes sont nécessaires : l'un sans l'autre
laisserait une brèche.

### La difficulté rencontrée
authorizeResource() dans le constructeur du contrôleur provoquait une erreur
"Call to undefined method PublicationController::middleware()". Ce comportement n'est pas
mentionné explicitement dans certaines versions du guide : dans Laravel 11 et 12, la classe
Controller de base est vide et ne fournit plus la méthode middleware() par défaut, alors que
authorizeResource() en dépend en interne.

### Comment je l'ai résolue
J'ai remplacé authorizeResource() par des appels explicites à $this->authorize() au début de
chaque méthode du contrôleur (index, create, store, show, destroy), avec le même résultat
mais sans dépendre du système de middleware du contrôleur. J'ai ensuite validé le
cloisonnement avec le test décrit dans le guide : connectée avec Awa (Groupe A), j'ai noté
l'identifiant d'une publication, puis connectée avec Fatou (Groupe B) dans un autre profil
Chrome, j'ai tenté d'accéder directement à cette URL. Le résultat a été une erreur 403, ce
qui confirme que la policy fonctionne correctement.


## Phase 6 — L'entraide, questions et réponses

Branche : feat/06-entraide
Dates : 23 août 2026

### Ce que j'ai fait
Créé le QuestionController (index, create, store, show), le ReponseController (store, destroy)
et le ReponseRetenueController, un contrôleur dédié à l'action de désigner une réponse comme
retenue. Ajouté la méthode designerReponse à la PublicationPolicy, les vues de l'entraide, et
testé le flux complet : poser une question, y répondre, marquer une réponse comme retenue
avec crédit de 10 points à son auteur.

### Pourquoi je l'ai fait ainsi
J'ai séparé la désignation de la réponse retenue dans son propre contrôleur plutôt que
d'ajouter une méthode au QuestionController, comme recommandé : ce n'est pas vraiment
"modifier une question", c'est une action à part avec ses propres règles de droits (seul
l'auteur de la question peut le faire).

### La difficulté rencontrée
La commande make:controller de cette version de Laravel n'accepte pas l'option --only utilisée
seule pour restreindre les méthodes générées. J'ai dû générer des contrôleurs simples et
écrire moi-même les méthodes store() et destroy() dont j'avais besoin.

### Comment je l'ai résolue
J'ai généré les contrôleurs sans options particulières, puis rempli manuellement chaque
méthode selon le rôle attendu. J'ai aussi veillé à garder la vérification abort_unless() dans
ReponseRetenueController, qui empêche de désigner comme retenue une réponse appartenant à
une autre question.

## Phase 7 — Modération automatique (OpenRouter)

- Création d'un compte OpenRouter et génération d'une clé API
- Ajout de openrouter dans config/services.php
- Création de App\Services\ModerationService qui interroge l'API OpenRouter (modèle openrouter/free, routeur auto gratuit) pour détecter insultes, harcèlement, discours haineux, spam et contenu sexuel explicite
- Comportement fail-open : en cas d'erreur API, le contenu est laissé passer pour ne pas bloquer la disponibilité du site
- Branchement dans StorePublicationRequest (via after()) pour les publications et questions, et directement dans ReponseController pour les réponses
- Validation du bon fonctionnement via Tinker (contenu correct accepté, contenu insultant refusé avec raison)
- Deux modèles gratuits initialement choisis sont devenus indisponibles en cours de route (404) ; passage au routeur automatique openrouter/free pour plus de robustesse

## Phase 8 — Signalements, quota IA et détection de doublon

- Création de SignalementController : un membre peut signaler une publication (motifs : spam, hors sujet, inapproprié, harcèlement, autre), sauf sa propre publication
- Utilisation de la Policy signaler() déjà existante pour l'autorisation
- Masquage automatique de la publication (statut = masque) une fois le seuil de signalements atteint (config seuil_signalement, 3 par défaut)
- L'auteur reste informé de sa publication masquée via une bannière dédiée dans la vue
- Ajout d'un quota IA quotidien par utilisateur dans ModerationService (config quota_ia_quotidien, 10/jour), suivi via le cache Laravel avec expiration à minuit
- Au-delà du quota (ou en cas d'erreur API), le comportement suit le réglage moderation_fail_open déjà défini en Phase 0
- Création de DoublonService : détecte si le contenu d'une nouvelle publication est très similaire (similar_text, seuil configurable à 92%) à une publication du même auteur postée dans les dernières 24h, après normalisation du texte (minuscules, ponctuation retirée)
- Branchement de la détection de doublon dans StorePublicationRequest, avant l'appel à la modération IA
- Validation de bout en bout via Tinker : signalement + masquage, quota atteint/non atteint, doublon détecté/non détecté

## Phase 9 — Réputation et épinglage

- Barème de points : publication +2, réponse +3, réponse retenue +10 (déjà en place depuis la Phase 8)
- Ajout de la méthode epingler() dans PublicationPolicy : seuil de réputation (config seuil_epinglage, 50 par défaut) et réservé aux propres publications de l'auteur
- Création de EpinglageController::toggle() : épingle/désépingle une publication (epingle_le à now() ou null)
- Route publications/{publication}/epingler ajoutée
- Le fil (PublicationController@index) trie déjà par epingle_le en premier, donc les publications épinglées remontent automatiquement
- Validation via Tinker : droit d'épingler refusé sous le seuil, refusé sur la publication d'un autre, accordé une fois le seuil atteint sur sa propre publication ; toggle testé