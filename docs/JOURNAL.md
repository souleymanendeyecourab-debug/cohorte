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