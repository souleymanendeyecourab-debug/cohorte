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
