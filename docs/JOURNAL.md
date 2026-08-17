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
