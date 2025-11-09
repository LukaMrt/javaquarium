# CLAUDE.md

Ce fichier fournit des directives à Claude Code (claude.ai/code) pour travailler sur ce dépôt.

## Vue d'ensemble du projet

JavaquariumECS est une simulation d'aquarium basée sur l'exercice de code Javaquarium (https://zestedesavoir.com/forums/sujet/447/javaquarium/). Le projet est développé avec Symfony 7.3 en suivant les principes de **Clean Architecture**, **Domain-Driven Development (DDD)**, **Test-Driven Development (TDD)** et **Clean Code**.

## Documentation technique

- **CLAUDE.md** (ce fichier) : Pratiques générales du projet
- **resources/ARCHITECTURE_TECHNIQUE.md** : Consignes techniques d'organisation et d'implémentation
- **resources/REGLES_JEU.md** : Règles complètes du jeu à suivre

## Stack technique

- **Framework** : Symfony 7.3 (MicroKernelTrait)
- **Langage** : PHP 8.4+ (utilisation des fonctionnalités modernes de PHP 8)
- **ORM** : Doctrine ORM 3.5 avec mapping par attributs
- **Base de données** : PostgreSQL 18 (via Docker Compose)
- **Frontend** : Symfony UX avec Stimulus.js et Turbo
- **Tests** : PHPUnit 12.4
- **Analyse statique** : PHPStan level 9

## Commandes courantes

### Serveur de développement
```bash
# Démarrer le serveur sur le port 8000
composer dev
# ou
symfony server:start --port=8000

# Arrêter le serveur
composer stop
# ou
symfony server:stop
```

### Tests
```bash
# Exécuter tous les tests
composer test

# Exécuter un test spécifique
composer test --filter testName
```

### Analyse statique
```bash
# Analyser avec PHPStan
composer stan
# ou
php vendor/bin/phpstan analyse src tests
```

### Opérations base de données
```bash
# Créer la base de données
php bin/console doctrine:database:create

# Générer une migration à partir des entités
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Annuler la dernière migration
php bin/console doctrine:migrations:migrate prev
```

### Génération de code (Symfony Maker)
```bash
# Créer une entité
php bin/console make:entity

# Créer un contrôleur
php bin/console make:controller

# Créer un formulaire
php bin/console make:form

# Créer un repository
php bin/console make:repository

# Créer un service
php bin/console make:service

# Créer un contrôleur Stimulus
php bin/console make:stimulus-controller
```

### Services Docker
```bash
# Démarrer PostgreSQL et Mailpit
docker compose up -d

# Arrêter les services
docker compose down

# Voir les logs
docker compose logs -f
```

### Gestion du cache
```bash
# Vider le cache
php bin/console cache:clear

# Préchauffer le cache
php bin/console cache:warmup
```

## Principes de développement

### Clean Architecture

Le projet suit une architecture en couches strictement découplées :

1. **Domaine métier** (Domain) : Isolé, sans dépendances externes
2. **Application** : Use cases et logique applicative
3. **Infrastructure** : Implémentations concrètes (Doctrine, Symfony, etc.)
4. **Présentation** : Contrôleurs, vues, API

**Règle fondamentale** : Les dépendances vont toujours de l'extérieur vers l'intérieur. Le domaine ne doit jamais dépendre de l'infrastructure.

### Test-Driven Development (TDD)

**Workflow obligatoire** :
1. **Red** : Écrire un test qui échoue
2. **Green** : Écrire le code minimal pour faire passer le test
3. **Refactor** : Améliorer le code tout en gardant les tests au vert

**Règles** :
- Toujours écrire les tests AVANT le code de production
- Un test par comportement
- Tests unitaires pour le domaine (pas de dépendances externes)
- Tests d'intégration pour l'infrastructure
- Viser une couverture de code de 100%
- Vérifier la couverture avec `composer test:coverage:text`

### Domain-Driven Development (DDD)

**Concepts à appliquer** :
- **Entities** : Objets avec identité unique
- **Value Objects** : Objets immuables sans identité
- **Aggregates** : Groupes d'entités traitées comme une unité
- **Repositories** : Interfaces de persistance (dans le domaine, implémentées dans l'infrastructure)
- **Services de domaine** : Logique métier qui ne rentre pas dans les entités
- **Domain Events** : Événements métier pour découpler les composants

### Clean Code

**Principes à respecter** :

1. **Pas de duplication** : DRY (Don't Repeat Yourself)
2. **Nommage explicite** : Les noms doivent révéler l'intention
3. **Fonctions courtes** : Une fonction = une responsabilité (SRP)
4. **Commentaires minimum** : Le code doit s'auto-documenter. N'ajouter des commentaires que s'ils apportent une réelle plus-value (explication d'un algorithme complexe, justification d'un choix technique non-évident). Les commentaires évidents ou qui répètent le code sont à proscrire.
5. **Gestion des erreurs** : Utiliser des exceptions métier explicites
6. **Optimisation** : Code propre d'abord, optimisation ensuite si nécessaire
7. **SOLID** : Respecter les 5 principes de conception objet

### Standards de qualité du code

**Obligatoire** :
- `declare(strict_types=1);` dans tous les fichiers PHP
- Type hints stricts sur tous les paramètres
- Return types déclarés sur toutes les méthodes
- Propriétés typées (PHP 8.4)
- PHPStan level 10 sans erreurs
- Couverture de tests > 80%

**Conventions de nommage** :
- **Langue du code** : Anglais (classes, méthodes, variables, commentaires de code)
- **Langue de la documentation** : Français (README, CLAUDE.md, commits, rapports)
- Classes : PascalCase
- Méthodes/fonctions : camelCase
- Constantes : UPPER_SNAKE_CASE
- Colonnes BDD : snake_case (configuré dans doctrine.yaml)

## Organisation du code

### Structure attendue

**Architecture Clean en couches** :
```
src/
├── Domain/          # Couche domaine (cœur métier isolé)
├── Application/     # Couche application (use cases)
├── Infrastructure/  # Couche infrastructure (implémentations)
└── Presentation/    # Couche présentation (contrôleurs, API)

tests/
├── Domain/          # Tests unitaires du domaine
├── Application/     # Tests des use cases
├── Infrastructure/  # Tests d'intégration
└── Presentation/    # Tests fonctionnels
```

**Règle importante** : La structure des tests **suit exactement** celle de `src/`. Pour chaque classe dans `src/`, il existe un test correspondant dans `tests/` avec le même chemin relatif.

Voir le fichier `resources/ARCHITECTURE_TECHNIQUE.md` pour l'organisation détaillée des répertoires et la structure des couches.

### Configuration Symfony

- **Autowiring** : Tous les services dans `src/` sont automatiquement enregistrés
- **Attributs PHP 8** : Utiliser les attributs pour le routing, Doctrine, validation (pas d'annotations YAML/XML)
- **Configuration par environnement** : `config/packages/{environment}/`

### Frontend

- **AssetMapper** : Imports JavaScript modernes sans build Node.js
- **Stimulus.js** : Framework JavaScript léger pour ajouter des comportements
- **Turbo** : Navigation SPA sans framework JavaScript complet
- **Contrôleurs Stimulus** : Dans `assets/controllers/`

## Workflow de développement

### Ajout d'une nouvelle fonctionnalité (approche TDD/DDD)

1. **Comprendre le besoin métier** (référencer `resources/REGLES_JEU.md`)
2. **Écrire les tests du domaine** (tests unitaires)
3. **Implémenter le domaine** (entities, value objects, services)
4. **Écrire les tests de l'application** (use cases)
5. **Implémenter la couche application**
6. **Écrire les tests d'infrastructure** (repositories, adapters)
7. **Implémenter l'infrastructure** (Doctrine, etc.)
8. **Implémenter la présentation** (contrôleurs, vues)
9. **Refactoring** si nécessaire

### Workflow Git

- **Branche principale** : `master`
- **Branche actuelle** : `symfony` (développement en cours)
- **Commits** : Messages clairs et descriptifs en français
- **Référence historique** : La branche `ECS` contient une implémentation Java pour référence

## Variables d'environnement

Variables clés dans `.env` :
- `APP_ENV` : Environnement (dev/test/prod)
- `DATABASE_URL` : Chaîne de connexion PostgreSQL
- `MESSENGER_TRANSPORT_DSN` : Transport pour les messages asynchrones

Variables Docker Compose :
- `POSTGRES_DB=javaquarium`
- `POSTGRES_USER=javaquarium-user`
- `POSTGRES_PASSWORD` : À définir dans .env

## Outils de développement

- **PHPStan** : Analyse statique stricte (level 9)
- **PHPUnit** : Framework de tests avec couverture de code
- **Xdebug** : Extension PHP pour la couverture de code (activer avec `XDEBUG_MODE=coverage`)
- **Symfony Maker** : Génération de code (utiliser avec parcimonie, préférer l'écriture manuelle pour respecter l'architecture)
- **Doctrine Migrations** : Gestion des versions de BDD
- **Web Profiler** : Débogage (dev uniquement)
- **Mailpit** : Test des emails (http://localhost:8025)

## Points d'attention

### Ce qu'il faut TOUJOURS faire

- Écrire les tests AVANT le code
- Isoler le domaine métier (pas de dépendances Symfony/Doctrine dans le domaine)
- Respecter les principes SOLID
- Utiliser des interfaces pour les dépendances
- Faire des commits atomiques et cohérents
- Documenter les décisions architecturales importantes
- Vérifier PHPStan avant chaque commit

### Ce qu'il faut ÉVITER

- Dupliquer le code
- Coupler le domaine à l'infrastructure
- Utiliser `make:entity` de Symfony pour les entités du domaine (créer manuellement)
- Mettre la logique métier dans les contrôleurs
- Ignorer les warnings PHPStan
- Committer du code non testé
- Utiliser des types mixtes ou any

## Contexte historique

La branche `ECS` contient une implémentation Java complète avec Ashley ECS framework qui peut servir de référence pour comprendre les mécaniques du jeu. Cependant, la nouvelle implémentation Symfony doit suivre les principes DDD/Clean Architecture, pas le pattern ECS.

## Notes spécifiques au projet

- Projet en phase d'initialisation : infrastructure configurée, logique métier à implémenter
- Base de données : PostgreSQL 18 avec connection pooling en production
- Toutes les communications (code, commits, documentation) en français
- Référencer les fichiers techniques pour les détails d'implémentation
