# REGLES_JEU.md

Ce fichier contient les règles complètes du jeu Javaquarium basées sur l'exercice original de Zeste de Savoir.

**Source** : https://zestedesavoir.com/forums/sujet/447/javaquarium/

---

## Objectif du jeu

Créer un simulateur d'aquarium où le joueur peut ajouter des poissons et des plantes, puis faire avancer le temps pour observer les interactions écologiques (alimentation, reproduction, vieillissement, mort).

---

## Structure du jeu

### Système basé sur les tours

Chaque tour s'exécute dans l'ordre suivant :

1. **Mise à jour** de tous les êtres vivants (vieillissement, points de vie)
2. **Phase de résolution** (alimentation, retrait des morts, reproduction)
3. **Rapport console** de l'état de l'aquarium

---

## Espèces de poissons et caractéristiques

### Poissons carnivores (mangent d'autres poissons)

| Espèce | Type de sexe |
|--------|--------------|
| **Merou** (Grouper) | Hermaphrodite protandre (changement d'âge) |
| **Thon** (Tuna) | Monosexué |
| **PoissonClown** (Clownfish) | Hermaphrodite opportuniste |

### Poissons herbivores (mangent des algues)

| Espèce | Type de sexe |
|--------|--------------|
| **Sole** (Sole) | Hermaphrodite opportuniste |
| **Bar** (Bass) | Hermaphrodite protandre (changement d'âge) |
| **Carpe** (Carp) | Monosexué |

---

## Attributs et mécaniques

### Points de vie (HP)

- Tous les êtres commencent avec **10 HP**
- La mort survient à **0 HP** (retrait instantané de l'aquarium)
- Espérance de vie maximale : **20 tours** (mort de vieillesse)
- Compteur d'âge : s'incrémente de **+1 à chaque tour**

### Système de faim des poissons

- Les poissons perdent **1 HP par tour** (représente la faim)
- Seuil de "faim" : **5 HP ou moins**
- Les poissons affamés cherchent de la nourriture
- Les poissons non affamés cherchent à se reproduire
- Chaque poisson fait **une seule tentative** d'alimentation OU de reproduction par tour

### Mécanique d'alimentation

**Sélection de cible** :
- La cible est sélectionnée **aléatoirement** parmi le pool disponible
- Un poisson **ne peut pas se manger lui-même**
- Un poisson **ne peut pas manger un membre de sa propre espèce**
- **Une seule tentative par tour** : si la cible aléatoire est invalide, pas de deuxième chance

**Gains/Pertes de HP** :

| Action | Effet |
|--------|-------|
| Herbivore mange algue | +3 HP |
| Carnivore mange poisson | +5 HP |
| Poisson attaqué | -4 HP (survit si HP > 4) |
| Algue mangée | -2 HP |

**Règles importantes** :
- La mort est **instantanée** : les êtres morts ne peuvent pas être consommés
- Manger et être mangé peuvent tous deux survenir dans le même tour
- Un poisson peut manger ET être mangé dans le même tour

### Croissance des algues

- Gagnent **+1 HP par tour** (croissance naturelle)
- Seuil de reproduction : **10+ HP**
- Se divise en **deux algues** avec la moitié des HP du parent chacune
- Le parent perd la moitié de ses HP lors de la reproduction
- L'algue parent **conserve son âge original** après division

### Règles de reproduction des poissons

**Mécanique de base** :
- Les poissons **non affamés** (HP > 5) ciblent aléatoirement un autre poisson
- Si **même espèce ET sexe opposé** → naissance d'un descendant
- **Une seule tentative de reproduction par tour et par poisson**
- Si le poisson A se reproduit avec le poisson B, le poisson B **ne peut pas** se reproduire avec un poisson C dans le même tour

**Types de sexe** :

1. **Monosexué** (Carpe, Thon)
   - Requiert un partenaire du sexe opposé de la même espèce
   - Le sexe est fixe toute la vie

2. **Hermaphrodite protandre** (Bar, Merou)
   - **Tours 0-10** : mâle
   - **Tours 11-20** : femelle
   - Le changement de sexe permet la reproduction à différents stades de vie

3. **Hermaphrodite opportuniste** (Sole, PoissonClown)
   - Change de sexe si la cible sélectionnée aléatoirement a le **même sexe actuel**
   - Permet la reproduction en s'adaptant au partenaire disponible

**Caractéristiques du descendant** :
- Même espèce que les parents
- Sexe aléatoire (mâle ou femelle)
- Âge = 0
- HP = 10

**Contrainte d'âge minimum** (clarification de la communauté) :
- Les poissons doivent avoir **au moins 2 tours d'âge** pour se reproduire
- Empêche les nouveau-nés de se reproduire le tour de leur naissance

---

## Phases d'implémentation progressives

L'exercice est conçu pour être développé progressivement en ajoutant des fonctionnalités par étapes.

### Phase 1 : Configuration de base de l'aquarium

**Objectifs** :
- Créer la structure de l'aquarium
- Permettre l'ajout de poissons et d'algues
- Afficher l'état de l'aquarium à chaque tour
- Faire avancer le temps (tours)

**Fonctionnalités minimales** :
- Liste des êtres vivants dans l'aquarium
- Affichage console basique
- Système de tours simple

### Phase 2 : Mécanique d'alimentation

**Objectifs** :
- Implémenter la sélection aléatoire de cibles
- Différencier carnivores et herbivores
- Gérer les gains/pertes de HP

**Fonctionnalités** :
- Système de faim (HP ≤ 5)
- Logique d'alimentation avec sélection aléatoire
- Validation des cibles (pas soi-même, pas même espèce)
- Mise à jour des HP après alimentation

### Phase 3 : Cycle de vie complet

**Objectifs** :
- Système complet de points de vie
- Vieillissement et mort
- Reproduction avec gestion des sexes

**Fonctionnalités** :
- Perte de 1 HP par tour (faim)
- Mort à 0 HP (retrait instantané)
- Mort de vieillesse à 20 tours
- Reproduction avec règles de sexe par espèce
- Croissance et division des algues
- Contrainte d'âge minimum pour reproduction

### Phase 4 : Persistance et gestion avancée

**Objectifs** :
- Sauvegarde et chargement d'état
- Logs des événements
- Gestion dynamique

**Fonctionnalités** :
- Persistance de l'état de l'aquarium
- Chargement de l'état sauvegardé
- Log des événements (naissances, morts, alimentation)
- Gestion dynamique de la population
- Historique des tours

---

## Points d'implémentation importants

### Persistance des données

L'état de l'aquarium doit être persisté pour permettre :
- La sauvegarde et le chargement de parties
- L'historique des événements (naissances, morts, alimentation)
- Le suivi de l'évolution de la population au fil des tours

### Gestion des conflits

**Problème de modification concurrente** :
- Les organismes doivent "vivre leur vie" pendant que l'aquarium est mis à jour
- Il faut **cloner les listes** au début du tour pour travailler avec l'état original
- Évite les exceptions de modification concurrente lors de l'itération

**Ordre de traitement** :
1. Cloner l'état actuel
2. Traiter tous les événements (alimentation, reproduction, vieillissement)
3. Appliquer tous les changements (ajouts, retraits)
4. Afficher le rapport

### Tests et aléatoire

**Défi** : L'aspect aléatoire empêche l'écriture de tests unitaires déterministes.

**Solution** :
- Séparer la logique de génération de nombres aléatoires dans un composant injectable
- Permettre le remplacement par un générateur déterministe pour les tests
- Tester les comportements indépendamment de l'aléatoire

**Exemple de test** :
- Vérifier qu'un Merou ne peut pas manger une Sole (même si sélectionné aléatoirement)
- Vérifier qu'une Carpe mâle + Carpe femelle = nouveau descendant Carpe

### Équilibre du jeu

**Observation de la communauté** :
- Les espèces hermaphrodites opportunistes carnivores gagnent presque toujours
- Il peut être nécessaire d'ajuster les règles pour l'équilibre

**Suggestions d'équilibrage** :
- Ajuster les gains/pertes de HP
- Modifier les seuils de reproduction
- Ajouter des contraintes supplémentaires

---

## Clarifications et règles ambiguës

### Reproduction d'algues et âge

**Ambiguïté** : L'âge de l'algue augmente-t-il avant ou après la reproduction ?

**Impact** : Affecte si une algue nouvellement mature peut se reproduire le même tour.

**Recommandation** : Incrémenter l'âge en premier, puis vérifier les conditions de reproduction.

### Poisson mordu peut-il toujours manger ?

**Question** : Un poisson qui se fait mordre peut-il toujours manger un autre poisson le même tour ?

**Réponse** : Oui, manger et être mangé sont des actions indépendantes qui peuvent se produire le même tour.

### Reproduction entre affamé et non-affamé

**Question** : Si un poisson affamé rencontre un poisson non-affamé prêt à se reproduire, que se passe-t-il ?

**Réponse** : Le poisson affamé tente de manger, pas de se reproduire. La reproduction n'a lieu que si **les deux** poissons sont non-affamés.

### Capacité de l'aquarium

**Question** : Y a-t-il une limite au nombre d'êtres dans l'aquarium ?

**Réponse** : Non, la capacité est effectivement illimitée (ou très grande).

---

## Glossaire des espèces

| Nom français | Nom anglais | Type alimentation | Type sexe |
|--------------|-------------|-------------------|-----------|
| Merou | Grouper | Carnivore | Hermaphrodite protandre |
| Thon | Tuna | Carnivore | Monosexué |
| PoissonClown | Clownfish | Carnivore | Hermaphrodite opportuniste |
| Sole | Sole | Herbivore | Hermaphrodite opportuniste |
| Bar | Bass | Herbivore | Hermaphrodite protandre |
| Carpe | Carp | Herbivore | Monosexué |
| Algue | Algae | - | - (asexué) |

---

## Notes de conception

### Principes DDD à appliquer

- **Entities** : Fish, Algae (ont une identité unique)
- **Value Objects** : Species, Sex, Age, HealthPoints (immuables)
- **Aggregates** : Aquarium (gère le cycle de vie des entités)
- **Domain Services** : FeedingService, ReproductionService, AgingService
- **Domain Events** : FishBorn, FishDied, FishAte, AlgaeSplit

### Éviter l'héritage profond

L'exercice original force à réfléchir sur l'utilisation correcte des objets. Plutôt qu'une hiérarchie d'héritage lourde, privilégier la composition avec :

- **Strategy Pattern** pour les comportements (diet, reproduction)
- **Components** pour les attributs (age, health, sex)
- **Services** pour la logique métier complexe

### Séparation des préoccupations

- **Domaine** : Logique métier pure (règles de reproduction, alimentation, etc.)
- **Application** : Use cases (faire avancer le temps, ajouter un poisson)
- **Infrastructure** : Persistance, génération aléatoire, affichage
- **Présentation** : Interface utilisateur, API

---

## Exemple de déroulement d'un tour

**État initial** :
```
Tour 1:
- Nemo (PoissonClown, Carnivore, 5 tours, male, 6 HP) - Affamé
- Dory (Sole, Herbivore, 3 tours, female, 8 HP) - Non affamée
- Algue1 (8 HP)
```

**Actions** :
1. **Vieillissement** : Tous les êtres vieillissent de +1 tour
2. **Perte de faim** : Tous les poissons perdent 1 HP
3. **Nemo affamé** : Cible aléatoire = Dory → Attaque → Dory perd 4 HP, Nemo gagne 5 HP
4. **Dory non affamée** : Cherche reproduction → Cible aléatoire = Nemo → Espèces différentes, pas de reproduction
5. **Algue1** : Croissance +1 HP
6. **Vérification des morts** : Personne à 0 HP

**État final** :
```
Tour 2:
- Nemo (PoissonClown, Carnivore, 6 tours, male, 10 HP) - Non affamé
- Dory (Sole, Herbivore, 4 tours, female, 3 HP) - Affamée
- Algue1 (9 HP)
```

---

## Checklist d'implémentation

### Phase 1
- [ ] Créer l'entité Aquarium
- [ ] Créer l'entité Fish avec attributs de base
- [ ] Créer l'entité Algae
- [ ] Implémenter l'ajout d'êtres à l'aquarium
- [ ] Implémenter l'affichage de l'état
- [ ] Implémenter le système de tours

### Phase 2
- [ ] Implémenter le système de faim (HP ≤ 5)
- [ ] Créer le FeedingService
- [ ] Implémenter la sélection aléatoire de cibles
- [ ] Implémenter les règles d'alimentation (carnivore/herbivore)
- [ ] Implémenter les gains/pertes de HP
- [ ] Gérer les cibles invalides (soi-même, même espèce)

### Phase 3
- [ ] Implémenter la perte de 1 HP par tour
- [ ] Implémenter la mort à 0 HP
- [ ] Implémenter la mort de vieillesse (20 tours)
- [ ] Créer le ReproductionService
- [ ] Implémenter les types de sexe (monosexué, protandre, opportuniste)
- [ ] Implémenter la reproduction des poissons
- [ ] Implémenter la croissance et division des algues
- [ ] Implémenter la contrainte d'âge minimum (≥2 tours)

### Phase 4
- [ ] Implémenter la persistance des données
- [ ] Implémenter le chargement de l'état sauvegardé
- [ ] Implémenter les logs d'événements
- [ ] Implémenter l'historique des tours
- [ ] Implémenter la gestion dynamique de population

---

**Version** : 1.0
**Dernière mise à jour** : 2025-01-06
