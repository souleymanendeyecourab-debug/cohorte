# Décisions techniques — Projet Cohorte

Ce document consigne les choix techniques structurants pris pendant le développement, avec leur justification.

## 1. Modération IA : fail-closed par défaut

**Contexte** : que faire si OpenRouter ne répond pas (panne, quota API dépassé, réponse illisible) ?

**Décision** : `moderation_fail_open = false` par défaut. En cas d'échec de la modération, le contenu est **refusé** plutôt que publié automatiquement.

**Justification** : dans un contexte étudiant où la modération protège contre le harcèlement et les propos inappropriés, il est préférable de bloquer temporairement une publication légitime (l'utilisateur peut réessayer) plutôt que de laisser passer un contenu potentiellement problématique sans aucun contrôle. Le coût d'un faux refus (frustration ponctuelle) est jugé inférieur au coût d'un faux laisser-passer (contenu nuisible publié).

**Alternative écartée** : fail-open (laisser passer en cas d'erreur), qui privilégierait la disponibilité au détriment de la sécurité du contenu.

## 2. Modèle OpenRouter : routeur automatique plutôt que modèle fixe

**Contexte** : les modèles gratuits disponibles sur OpenRouter changent fréquemment (deux modèles initialement choisis, `meta-llama/llama-3.1-8b-instruct:free` puis `meta-llama/llama-3.2-3b-instruct:free`, sont devenus indisponibles en quelques jours).

**Décision** : utilisation du routeur automatique `openrouter/free`, qui sélectionne lui-même un modèle gratuit disponible à chaque appel, plutôt que de figer un modèle précis dans la configuration.

**Justification** : cette approche rend le service de modération résilient aux retraits de modèles côté fournisseur, sans nécessiter de surveillance manuelle ni de mise à jour de configuration à chaque changement.

**Alternative écartée** : épingler un modèle précis, plus prévisible en théorie mais fragile face aux évolutions du catalogue gratuit d'OpenRouter.

## 3. Quota IA : comportement au dépassement aligné sur le fail-open/fail-closed existant

**Contexte** : chaque membre a un quota quotidien d'appels à la modération IA (10 par défaut). Que se passe-t-il pour sa publication suivante une fois ce quota atteint ?

**Décision** : le dépassement du quota déclenche le même comportement que l'indisponibilité de l'API (`moderation_fail_open`), plutôt qu'une règle distincte.

**Justification** : évite de multiplier les réglages de configuration pour des situations qui reviennent, du point de vue de l'utilisateur, au même résultat : la modération automatique n'a pas pu s'exécuter pour ce contenu.

**Alternative écartée** : un comportement séparé et configurable pour le dépassement de quota, jugé superflu pour la taille du projet.

## 4. Détection de doublon : comparaison limitée au même auteur, sur 24h

**Contexte** : comment détecter qu'un membre republie un contenu très similaire (spam personnel) ?

**Décision** : comparaison uniquement entre les publications du **même auteur**, sur une fenêtre de **24 heures**, avec normalisation du texte (minuscules, ponctuation retirée) et seuil de similarité à 92% (`similar_text`).

**Justification** : une comparaison inter-utilisateurs (deux membres différents postant un sujet proche) n'est pas un signal de spam mais un usage normal d'un forum d'entraide. Limiter la fenêtre à 24h évite d'empêcher un membre de reposer un sujet proche des semaines plus tard. Le seuil à 92% (relevé depuis un essai initial à 85%, qui produisait des faux positifs sur des phrases courtes sans lien de sens) réduit les faux positifs tout en couvrant les cas de republication quasi-identique.

**Alternative écartée** : comparaison à l'échelle de toute la promotion, qui aurait signalé à tort des sujets similaires posés par des auteurs différents.

## 5. Épinglage : réservé aux propres publications de l'auteur

**Contexte** : une fois un seuil de réputation atteint (50 points par défaut), que permet ce droit ?

**Décision** : le membre peut épingler/désépingler uniquement **ses propres** publications, pas celles des autres membres de la promotion.

**Justification** : l'épinglage récompense l'implication d'un membre en lui donnant plus de visibilité sur son propre contenu de valeur, sans lui donner un pouvoir de modération sur le contenu d'autrui (qui reste réservé aux délégués et enseignants via les autres mécanismes du projet : signalement, suppression).