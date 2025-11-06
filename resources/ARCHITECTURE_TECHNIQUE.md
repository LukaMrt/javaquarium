# ARCHITECTURE_TECHNIQUE.md

Ce fichier définit les consignes techniques d'organisation et d'implémentation du projet JavaquariumECS.

**Référence** : Ce document complète `CLAUDE.md` (pratiques générales) et `REGLES_JEU.md` (règles métier).

---

## Table des matières

1. [Principes architecturaux](#principes-architecturaux)
2. [Structure des répertoires](#structure-des-répertoires)
3. [Couche Domain](#couche-domain)
4. [Couche Application](#couche-application)
5. [Couche Infrastructure](#couche-infrastructure)
6. [Couche Presentation](#couche-presentation)
7. [Dépendances entre couches](#dépendances-entre-couches)
8. [Conventions de code](#conventions-de-code)
9. [Configuration Symfony](#configuration-symfony)
10. [Base de données](#base-de-données)
11. [Tests](#tests)
12. [Implémentation progressive](#implémentation-progressive)

---

## Principes architecturaux

### Clean Architecture

Le projet suit une architecture hexagonale (ports & adapters) organisée en 4 couches concentriques :

```
┌─────────────────────────────────────────┐
│         Presentation (API/UI)           │
│  ┌───────────────────────────────────┐  │
│  │      Infrastructure (Tech)        │  │
│  │  ┌─────────────────────────────┐  │  │
│  │  │   Application (Use Cases)   │  │  │
│  │  │  ┌───────────────────────┐  │  │  │
│  │  │  │   Domain (Business)   │  │  │  │
│  │  │  │                       │  │  │  │
│  │  │  └───────────────────────┘  │  │  │
│  │  └─────────────────────────────┘  │  │
│  └───────────────────────────────────┘  │
└─────────────────────────────────────────┘
```

**Règle fondamentale** : Les dépendances pointent toujours **vers l'intérieur**.

- **Domain** : Ne dépend de RIEN (logique métier pure)
- **Application** : Dépend uniquement du Domain
- **Infrastructure** : Dépend du Domain et de l'Application
- **Presentation** : Dépend de l'Application

### Inversion de dépendances

Les interfaces sont définies dans les couches internes, implémentées dans les couches externes :

```php
// Domain/Fish/Repository/FishRepositoryInterface.php
namespace App\Domain\Fish\Repository;

interface FishRepositoryInterface {
    public function save(Fish $fish): void;
    public function findById(FishId $id): ?Fish;
}

// Infrastructure/Persistence/Doctrine/Repository/DoctrineFishRepository.php
namespace App\Infrastructure\Persistence\Doctrine\Repository;

class DoctrineFishRepository implements FishRepositoryInterface {
    // Implémentation Doctrine
}
```

---

## Structure des répertoires

### Vue d'ensemble

```
src/
├── Domain/                 # Logique métier (cœur de l'application)
├── Application/            # Use cases (orchestration métier)
├── Infrastructure/         # Implémentations techniques
└── Presentation/           # Points d'entrée (API, CLI)

tests/
├── Domain/                 # Tests unitaires (pas de dépendances)
├── Application/            # Tests des use cases
├── Infrastructure/         # Tests d'intégration
└── Presentation/           # Tests fonctionnels (API)
```

### Structure complète

```
src/
├── Domain/
│   ├── Shared/
│   │   ├── ValueObject/
│   │   │   ├── Age.php
│   │   │   ├── HealthPoints.php
│   │   │   ├── EntityName.php
│   │   │   └── EntityId.php
│   │   ├── Exception/
│   │   │   ├── DomainException.php
│   │   │   ├── InvalidAgeException.php
│   │   │   ├── InvalidHealthPointsException.php
│   │   │   └── EntityNotFoundException.php
│   │   └── GameRules.php
│   │
│   ├── Fish/
│   │   ├── Entity/
│   │   │   └── Fish.php
│   │   ├── ValueObject/
│   │   │   ├── FishId.php
│   │   │   ├── Species.php (Enum)
│   │   │   ├── Sex.php (Enum)
│   │   │   └── Diet.php (Enum)
│   │   ├── Strategy/
│   │   │   ├── SexualityBehaviorInterface.php
│   │   │   ├── FixedSexBehavior.php
│   │   │   ├── ProtandricHermaphroditeBehavior.php
│   │   │   └── OpportunisticHermaphroditeBehavior.php
│   │   └── Repository/
│   │       └── FishRepositoryInterface.php
│   │
│   ├── Algae/
│   │   ├── Entity/
│   │   │   └── Algae.php
│   │   ├── ValueObject/
│   │   │   └── AlgaeId.php
│   │   └── Repository/
│   │       └── AlgaeRepositoryInterface.php
│   │
│   ├── Aquarium/
│   │   ├── Entity/
│   │   │   └── Aquarium.php (Aggregate Root)
│   │   ├── ValueObject/
│   │   │   ├── AquariumId.php
│   │   │   └── TurnNumber.php
│   │   └── Repository/
│   │       └── AquariumRepositoryInterface.php
│   │
│   ├── Event/
│   │   ├── Entity/
│   │   │   └── GameEvent.php
│   │   ├── ValueObject/
│   │   │   ├── EventId.php
│   │   │   └── EventType.php (Enum)
│   │   └── Repository/
│   │       └── EventRepositoryInterface.php
│   │
│   └── Service/
│       ├── RandomGeneratorInterface.php
│       ├── NameGeneratorInterface.php
│       ├── AgingService.php
│       ├── HungerService.php
│       ├── FeedingService.php
│       ├── ReproductionService.php
│       ├── AlgaeGrowthService.php
│       ├── DeathService.php
│       └── TurnOrchestrator.php
│
├── Application/
│   ├── UseCase/
│   │   ├── Aquarium/
│   │   │   ├── CreateAquarium/
│   │   │   │   ├── CreateAquariumCommand.php
│   │   │   │   ├── CreateAquariumHandler.php
│   │   │   │   └── CreateAquariumResponse.php
│   │   │   ├── GetAquariumState/
│   │   │   │   ├── GetAquariumStateQuery.php
│   │   │   │   ├── GetAquariumStateHandler.php
│   │   │   │   └── GetAquariumStateResponse.php
│   │   │   └── AdvanceTurn/
│   │   │       ├── AdvanceTurnCommand.php
│   │   │       ├── AdvanceTurnHandler.php
│   │   │       └── AdvanceTurnResponse.php
│   │   ├── Fish/
│   │   │   └── AddFishToAquarium/
│   │   │       ├── AddFishCommand.php
│   │   │       ├── AddFishHandler.php
│   │   │       └── AddFishResponse.php
│   │   └── Algae/
│   │       └── AddAlgaeToAquarium/
│   │           ├── AddAlgaeCommand.php
│   │           ├── AddAlgaeHandler.php
│   │           └── AddAlgaeResponse.php
│   └── DTO/
│       ├── FishDTO.php
│       ├── AlgaeDTO.php
│       └── AquariumStateDTO.php
│
├── Infrastructure/
│   ├── Persistence/
│   │   ├── Doctrine/
│   │   │   ├── Repository/
│   │   │   │   ├── DoctrineFishRepository.php
│   │   │   │   ├── DoctrineAlgaeRepository.php
│   │   │   │   ├── DoctrineAquariumRepository.php
│   │   │   │   └── DoctrineEventRepository.php
│   │   │   └── Type/
│   │   │       ├── FishIdType.php
│   │   │       ├── AlgaeIdType.php
│   │   │       ├── AquariumIdType.php
│   │   │       ├── SpeciesType.php
│   │   │       └── SexType.php
│   │   └── Migration/
│   │       └── (fichiers de migration Doctrine)
│   │
│   ├── Service/
│   │   ├── SymfonyRandomGenerator.php
│   │   └── FakerNameGenerator.php
│   │
│   └── Symfony/
│       └── (configurations spécifiques si nécessaire)
│
└── Presentation/
    ├── Api/
    │   ├── Controller/
    │   │   ├── AquariumController.php
    │   │   ├── FishController.php
    │   │   └── AlgaeController.php
    │   ├── Request/
    │   │   ├── CreateAquariumRequest.php
    │   │   ├── AddFishRequest.php
    │   │   └── AddAlgaeRequest.php
    │   └── Transformer/
    │       ├── FishTransformer.php
    │       ├── AlgaeTransformer.php
    │       └── AquariumTransformer.php
    │
    └── Cli/
        └── Command/
            └── SimulateAquariumCommand.php
```

---

## Couche Domain

### Responsabilités

- Contient la **logique métier pure**
- **Aucune dépendance** vers Symfony, Doctrine, ou toute bibliothèque externe (sauf `symfony/uid` pour les UUID)
- Définit les **interfaces** (repositories, services techniques)
- **100% testable** sans infrastructure

### Organisation

#### Entities

**Règles** :
- Possèdent une **identité unique** (ID)
- Contiennent la **logique métier** les concernant
- **Immuables** autant que possible (méthodes retournent de nouvelles instances)
- Utilisent des **Value Objects** pour les attributs

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Fish\Entity;

use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\HealthPoints;
use App\Domain\Shared\ValueObject\EntityName;

final class Fish
{
    private function __construct(
        private FishId $id,
        private EntityName $name,
        private Species $species,
        private Sex $sex,
        private Age $age,
        private HealthPoints $healthPoints
    ) {}

    public static function create(
        FishId $id,
        EntityName $name,
        Species $species,
        Sex $sex
    ): self {
        return new self(
            $id,
            $name,
            $species,
            $sex,
            Age::fromInt(0),
            HealthPoints::initial()
        );
    }

    public function age(): self {
        return new self(
            $this->id,
            $this->name,
            $this->species,
            $this->sex,
            $this->age->increment(),
            $this->healthPoints
        );
    }

    public function isHungry(): bool {
        return $this->healthPoints->isHungry();
    }

    // Getters...
}
```

#### Value Objects

**Règles** :
- **Immuables** (pas de setters)
- **Auto-validants** (validation dans le constructeur)
- Implémentent l'**égalité par valeur**
- Peuvent contenir de la **logique métier**

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\InvalidHealthPointsException;
use App\Domain\Shared\GameRules;

final readonly class HealthPoints
{
    private function __construct(
        private int $value
    ) {
        if ($value < 0 || $value > GameRules::MAX_HP) {
            throw new InvalidHealthPointsException(
                sprintf('Health points must be between 0 and %d, got %d', GameRules::MAX_HP, $value)
            );
        }
    }

    public static function fromInt(int $value): self {
        return new self($value);
    }

    public static function initial(): self {
        return new self(GameRules::INITIAL_HP);
    }

    public function add(int $amount): self {
        $newValue = min($this->value + $amount, GameRules::MAX_HP);
        return new self($newValue);
    }

    public function subtract(int $amount): self {
        $newValue = max($this->value - $amount, 0);
        return new self($newValue);
    }

    public function isHungry(): bool {
        return $this->value <= GameRules::HUNGER_THRESHOLD;
    }

    public function isDead(): bool {
        return $this->value === 0;
    }

    public function toInt(): int {
        return $this->value;
    }

    public function equals(self $other): bool {
        return $this->value === $other->value;
    }
}
```

#### Enums

**Utilisation** : Types avec ensemble fini de valeurs + comportements

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Fish\ValueObject;

use App\Domain\Fish\Strategy\SexualityBehaviorInterface;
use App\Domain\Fish\Strategy\FixedSexBehavior;
use App\Domain\Fish\Strategy\ProtandricHermaphroditeBehavior;
use App\Domain\Fish\Strategy\OpportunisticHermaphroditeBehavior;

enum Species: string
{
    case GROUPER = 'grouper';
    case TUNA = 'tuna';
    case CLOWNFISH = 'clownfish';
    case SOLE = 'sole';
    case BASS = 'bass';
    case CARP = 'carp';

    public function getDiet(): Diet
    {
        return match($this) {
            self::GROUPER, self::TUNA, self::CLOWNFISH => Diet::CARNIVORE,
            self::SOLE, self::BASS, self::CARP => Diet::HERBIVORE,
        };
    }

    public function getSexualityBehavior(): SexualityBehaviorInterface
    {
        return match($this) {
            self::TUNA, self::CARP => new FixedSexBehavior(),
            self::GROUPER, self::BASS => new ProtandricHermaphroditeBehavior(),
            self::CLOWNFISH, self::SOLE => new OpportunisticHermaphroditeBehavior(),
        };
    }

    public function getDisplayName(): string
    {
        return match($this) {
            self::GROUPER => 'Mérou',
            self::TUNA => 'Thon',
            self::CLOWNFISH => 'Poisson-clown',
            self::SOLE => 'Sole',
            self::BASS => 'Bar',
            self::CARP => 'Carpe',
        };
    }
}
```

#### Identifiants (EntityId)

**Règles** :
- Basés sur **Symfony UUID v7** (temporellement ordonnés)
- Wrappés dans des classes spécifiques (`FishId`, `AlgaeId`, etc.)
- Type-safe (impossible de confondre un FishId avec un AlgaeId)

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Fish\ValueObject;

use App\Domain\Shared\ValueObject\EntityId;
use Symfony\Component\Uid\UuidV7;

final readonly class FishId extends EntityId
{
    private function __construct(UuidV7 $uuid)
    {
        parent::__construct($uuid);
    }

    public static function generate(): self
    {
        return new self(new UuidV7());
    }

    public static function fromString(string $id): self
    {
        return new self(UuidV7::fromString($id));
    }
}
```

#### Strategy Pattern

**Utilisation** : Comportements polymorphes (hermaphrodisme)

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Fish\Strategy;

use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Shared\ValueObject\Age;

interface SexualityBehaviorInterface
{
    public function getCurrentSex(Sex $initialSex, Age $age): Sex;
    public function canChangeSexForReproduction(Sex $currentSex, Sex $partnerSex): bool;
    public function getNewSexForReproduction(Sex $currentSex, Sex $partnerSex): Sex;
}
```

#### Services du Domain

**Règles** :
- Logique métier qui **ne rentre pas** dans les entités
- **Stateless** (sans état)
- Dépendent d'autres services ou repositories via **interfaces**

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Algae\Entity\Algae;
use App\Domain\Shared\GameRules;

final readonly class FeedingService
{
    public function feed(Fish $fish, Fish|Algae $target): FeedingResult
    {
        // Validation
        if (!$this->canFeed($fish, $target)) {
            return FeedingResult::failed('Cannot feed on this target');
        }

        // Application des règles métier
        $hpGain = $target instanceof Algae
            ? GameRules::HERBIVORE_HP_GAIN
            : GameRules::CARNIVORE_HP_GAIN;

        $hpLoss = $target instanceof Algae
            ? GameRules::ALGAE_HP_LOSS
            : GameRules::FISH_HP_LOSS;

        $updatedFish = $fish->gainHealth($hpGain);
        $updatedTarget = $target->loseHealth($hpLoss);

        return FeedingResult::success($updatedFish, $updatedTarget);
    }

    public function canFeed(Fish $fish, Fish|Algae $target): bool
    {
        // Règles de validation
        if ($target instanceof Fish) {
            if ($fish->getId()->equals($target->getId())) {
                return false; // Cannot eat itself
            }
            if ($fish->getSpecies() === $target->getSpecies()) {
                return false; // Cannot eat same species
            }
            if ($fish->getSpecies()->getDiet() === Diet::HERBIVORE) {
                return false; // Herbivores don't eat fish
            }
        }

        if ($target instanceof Algae && $fish->getSpecies()->getDiet() === Diet::CARNIVORE) {
            return false; // Carnivores don't eat algae
        }

        return true;
    }
}
```

#### GameRules

**Centralisation** de toutes les constantes métier :

```php
declare(strict_types=1);

namespace App\Domain\Shared;

final class GameRules
{
    // Health Points
    public const int INITIAL_HP = 10;
    public const int MAX_HP = 10;
    public const int HUNGER_THRESHOLD = 5;
    public const int HP_LOSS_PER_TURN = 1;

    // Age
    public const int MAX_AGE = 20;
    public const int MIN_REPRODUCTION_AGE = 2;
    public const int SEX_CHANGE_AGE = 10; // For protandric hermaphrodites

    // Feeding
    public const int HERBIVORE_HP_GAIN = 3;
    public const int CARNIVORE_HP_GAIN = 5;
    public const int FISH_HP_LOSS = 4;
    public const int ALGAE_HP_LOSS = 2;

    // Algae
    public const int ALGAE_GROWTH_HP = 1;
    public const int ALGAE_SPLIT_THRESHOLD = 10;

    private function __construct() {}
}
```

#### Repositories (Interfaces)

**Règles** :
- Définies dans le **Domain**
- Implémentées dans l'**Infrastructure**
- Méthodes en langage métier (pas de SQL)

**Exemple** :
```php
declare(strict_types=1);

namespace App\Domain\Fish\Repository;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;

interface FishRepositoryInterface
{
    public function save(Fish $fish): void;
    public function findById(FishId $id): ?Fish;
    public function delete(Fish $fish): void;
    public function findAll(): array;
}
```

---

## Couche Application

### Responsabilités

- Orchestre les **use cases** (scénarios utilisateur)
- **N'a PAS** de logique métier (délègue au Domain)
- Coordonne Domain + Infrastructure
- Gère les **transactions**
- Transforme les données (DTO)

### Pattern CQRS

Séparation **Commands** (écriture) et **Queries** (lecture) :

```
UseCase/
├── CreateAquarium/           # Command (écriture)
│   ├── CreateAquariumCommand.php
│   ├── CreateAquariumHandler.php
│   └── CreateAquariumResponse.php
└── GetAquariumState/         # Query (lecture)
    ├── GetAquariumStateQuery.php
    ├── GetAquariumStateHandler.php
    └── GetAquariumStateResponse.php
```

### Structure d'un Use Case

**1. Command/Query** (entrée)

```php
declare(strict_types=1);

namespace App\Application\UseCase\Fish\AddFishToAquarium;

use App\Domain\Fish\ValueObject\Species;
use App\Domain\Fish\ValueObject\Sex;

final readonly class AddFishCommand
{
    public function __construct(
        public string $aquariumId,
        public string $name,
        public Species $species,
        public Sex $sex
    ) {}
}
```

**2. Handler** (traitement)

```php
declare(strict_types=1);

namespace App\Application\UseCase\Fish\AddFishToAquarium;

use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\Exception\EntityNotFoundException;

final readonly class AddFishHandler
{
    public function __construct(
        private AquariumRepositoryInterface $aquariumRepository
    ) {}

    public function handle(AddFishCommand $command): AddFishResponse
    {
        $aquariumId = AquariumId::fromString($command->aquariumId);
        $aquarium = $this->aquariumRepository->findById($aquariumId);

        if ($aquarium === null) {
            throw new EntityNotFoundException('Aquarium not found');
        }

        $fish = Fish::create(
            FishId::generate(),
            EntityName::fromString($command->name),
            $command->species,
            $command->sex
        );

        $aquarium->addFish($fish);
        $this->aquariumRepository->save($aquarium);

        return new AddFishResponse($fish->getId()->toString());
    }
}
```

**3. Response** (sortie)

```php
declare(strict_types=1);

namespace App\Application\UseCase\Fish\AddFishToAquarium;

final readonly class AddFishResponse
{
    public function __construct(
        public string $fishId
    ) {}
}
```

### DTOs

**Utilisation** : Transférer des données entre couches

```php
declare(strict_types=1);

namespace App\Application\DTO;

final readonly class FishDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $species,
        public string $sex,
        public int $age,
        public int $healthPoints,
        public bool $isHungry
    ) {}

    public static function fromDomain(Fish $fish): self
    {
        return new self(
            $fish->getId()->toString(),
            $fish->getName()->toString(),
            $fish->getSpecies()->value,
            $fish->getSex()->value,
            $fish->getAge()->toInt(),
            $fish->getHealthPoints()->toInt(),
            $fish->isHungry()
        );
    }
}
```

---

## Couche Infrastructure

### Responsabilités

- **Implémentations concrètes** des interfaces du Domain
- Communication avec la **base de données** (Doctrine)
- Services **techniques** (aléatoire, génération de noms)
- **Pas de logique métier**

### Repositories Doctrine

**Mapping** : Utiliser des **types Doctrine custom** pour les Value Objects

**Type custom exemple** :

```php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Fish\ValueObject\FishId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Uid\UuidV7;

final class FishIdType extends Type
{
    public const string NAME = 'fish_id';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?FishId
    {
        if ($value === null) {
            return null;
        }

        return FishId::fromString($value);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof FishId) {
            throw new \InvalidArgumentException('Expected FishId');
        }

        return $value->toString();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
```

**Repository Doctrine** :

```php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\Repository\FishRepositoryInterface;
use App\Domain\Fish\ValueObject\FishId;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineFishRepository implements FishRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function save(Fish $fish): void
    {
        $this->entityManager->persist($fish);
        $this->entityManager->flush();
    }

    public function findById(FishId $id): ?Fish
    {
        return $this->entityManager->find(Fish::class, $id);
    }

    public function delete(Fish $fish): void
    {
        $this->entityManager->remove($fish);
        $this->entityManager->flush();
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(Fish::class)->findAll();
    }
}
```

### Services techniques

**RandomGenerator** :

```php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\RandomGeneratorInterface;

final class SymfonyRandomGenerator implements RandomGeneratorInterface
{
    public function selectRandom(array $items): mixed
    {
        if (empty($items)) {
            return null;
        }

        $index = array_rand($items);
        return $items[$index];
    }

    public function randomBoolean(): bool
    {
        return random_int(0, 1) === 1;
    }
}
```

**NameGenerator avec Faker** :

```php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Service\NameGeneratorInterface;
use Faker\Factory;
use Faker\Generator;

final class FakerNameGenerator implements NameGeneratorInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function generateFishName(): string
    {
        return $this->faker->firstName();
    }

    public function generateAlgaeName(): string
    {
        return 'Algue ' . $this->faker->word();
    }
}
```

---

## Couche Presentation

### Responsabilités

- **Points d'entrée** de l'application (API REST, CLI)
- **Validation** des requêtes
- **Transformation** des réponses (JSON)
- Gestion des **erreurs HTTP**
- **Pas de logique métier**

### API REST

**Structure** :

```
Presentation/Api/
├── Controller/
│   ├── AquariumController.php
│   ├── FishController.php
│   └── AlgaeController.php
├── Request/
│   ├── CreateAquariumRequest.php
│   ├── AddFishRequest.php
│   └── AddAlgaeRequest.php
└── Transformer/
    ├── FishTransformer.php
    └── AquariumTransformer.php
```

**Controller exemple** :

```php
declare(strict_types=1);

namespace App\Presentation\Api\Controller;

use App\Application\UseCase\Fish\AddFishToAquarium\AddFishCommand;
use App\Application\UseCase\Fish\AddFishToAquarium\AddFishHandler;
use App\Presentation\Api\Request\AddFishRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/aquariums/{aquariumId}/fishes', name: 'api_aquarium_add_fish', methods: ['POST'])]
final class AddFishController extends AbstractController
{
    public function __construct(
        private readonly AddFishHandler $handler
    ) {}

    public function __invoke(
        string $aquariumId,
        #[MapRequestPayload] AddFishRequest $request
    ): JsonResponse {
        $command = new AddFishCommand(
            $aquariumId,
            $request->name,
            $request->species,
            $request->sex
        );

        $response = $this->handler->handle($command);

        return new JsonResponse(
            ['fishId' => $response->fishId],
            Response::HTTP_CREATED
        );
    }
}
```

**Request DTO avec validation** :

```php
declare(strict_types=1);

namespace App\Presentation\Api\Request;

use App\Domain\Fish\ValueObject\Species;
use App\Domain\Fish\ValueObject\Sex;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddFishRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 50)]
        public string $name,

        #[Assert\NotBlank]
        public Species $species,

        #[Assert\NotBlank]
        public Sex $sex
    ) {}
}
```

### Routes API

```
POST   /api/aquariums                        # Créer un aquarium
GET    /api/aquariums/{id}                   # État de l'aquarium
POST   /api/aquariums/{id}/turn              # Avancer d'un tour
POST   /api/aquariums/{id}/fishes            # Ajouter un poisson
POST   /api/aquariums/{id}/algae             # Ajouter une algue
GET    /api/aquariums/{id}/events            # Historique des événements
DELETE /api/aquariums/{id}                   # Supprimer un aquarium
```

---

## Dépendances entre couches

### Diagramme de dépendances

```
┌─────────────────────────────────────────────┐
│           Presentation Layer                │
│  (Controllers, CLI Commands)                │
│                                             │
│  depends on ↓                               │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│          Application Layer                  │
│  (Use Cases, Handlers, DTOs)                │
│                                             │
│  depends on ↓                               │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│            Domain Layer                     │
│  (Entities, Value Objects, Services)        │
│  ← implements                               │
│                                             │
│  defines interfaces ↓                       │
└─────────────────────────────────────────────┘
                    ↓
┌─────────────────────────────────────────────┐
│         Infrastructure Layer                │
│  (Doctrine Repos, Technical Services)       │
│                                             │
│  implements interfaces ↑                    │
└─────────────────────────────────────────────┘
```

### Règles strictes

✅ **Autorisé** :
- Presentation → Application
- Application → Domain
- Infrastructure → Domain (via interfaces)
- Infrastructure → Application

❌ **Interdit** :
- Domain → Application
- Domain → Infrastructure
- Domain → Presentation
- Application → Infrastructure (sauf via interfaces)

---

## Conventions de code

### Nommage

**Classes** :
- PascalCase
- Noms descriptifs et explicites
- Suffixes : `Service`, `Repository`, `Handler`, `Controller`, `Request`, `Response`

**Méthodes** :
- camelCase
- Verbes d'action : `create()`, `findById()`, `handle()`, `execute()`

**Constantes** :
- UPPER_SNAKE_CASE
- Centralisées dans `GameRules`

**Variables** :
- camelCase
- Noms descriptifs

### Types stricts

**Obligatoire dans tous les fichiers** :

```php
declare(strict_types=1);
```

### Readonly

**Utiliser `readonly`** pour :
- Value Objects (immuabilité)
- DTOs (données en transit)
- Services (stateless)

```php
final readonly class HealthPoints { }
final readonly class AddFishCommand { }
final readonly class FeedingService { }
```

### Final

**Utiliser `final`** par défaut (empêche l'héritage non prévu) :

```php
final class Fish { }
final readonly class FishId { }
```

### Exceptions

**Hiérarchie** :

```
DomainException (abstract)
├── InvalidAgeException
├── InvalidHealthPointsException
├── EntityNotFoundException
└── InvalidOperationException
```

**Usage** :

```php
declare(strict_types=1);

namespace App\Domain\Shared\Exception;

abstract class DomainException extends \Exception {}
```

```php
declare(strict_types=1);

namespace App\Domain\Shared\Exception;

final class InvalidHealthPointsException extends DomainException {}
```

---

## Configuration Symfony

### services.yaml

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    # Domain Services
    App\Domain\Service\:
        resource: '../src/Domain/Service/*'

    # Application Handlers
    App\Application\UseCase\:
        resource: '../src/Application/UseCase/*'

    # Infrastructure
    App\Infrastructure\:
        resource: '../src/Infrastructure/*'
        exclude:
            - '../src/Infrastructure/Persistence/Doctrine/Type/*'

    # Bind interfaces to implementations
    App\Domain\Service\RandomGeneratorInterface:
        class: App\Infrastructure\Service\SymfonyRandomGenerator

    App\Domain\Service\NameGeneratorInterface:
        class: App\Infrastructure\Service\FakerNameGenerator

    # Repositories
    App\Domain\Fish\Repository\FishRepositoryInterface:
        class: App\Infrastructure\Persistence\Doctrine\Repository\DoctrineFishRepository

    App\Domain\Algae\Repository\AlgaeRepositoryInterface:
        class: App\Infrastructure\Persistence\Doctrine\Repository\DoctrineAlgaeRepository

    App\Domain\Aquarium\Repository\AquariumRepositoryInterface:
        class: App\Infrastructure\Persistence\Doctrine\Repository\DoctrineAquariumRepository
```

### doctrine.yaml

```yaml
doctrine:
    dbal:
        url: '%env(resolve:DATABASE_URL)%'
        types:
            fish_id: App\Infrastructure\Persistence\Doctrine\Type\FishIdType
            algae_id: App\Infrastructure\Persistence\Doctrine\Type\AlgaeIdType
            aquarium_id: App\Infrastructure\Persistence\Doctrine\Type\AquariumIdType
            species: App\Infrastructure\Persistence\Doctrine\Type\SpeciesType
            sex: App\Infrastructure\Persistence\Doctrine\Type\SexType
            diet: App\Infrastructure\Persistence\Doctrine\Type\DietType
            event_type: App\Infrastructure\Persistence\Doctrine\Type\EventTypeType

    orm:
        auto_generate_proxy_classes: true
        enable_lazy_ghost_objects: true
        naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
        auto_mapping: true
        mappings:
            Domain:
                is_bundle: false
                dir: '%kernel.project_dir%/src/Domain'
                prefix: 'App\Domain'
                alias: Domain
                type: attribute
```

### Enregistrer les types Doctrine

Dans `src/Kernel.php` :

```php
declare(strict_types=1);

namespace App;

use App\Infrastructure\Persistence\Doctrine\Type\FishIdType;
use App\Infrastructure\Persistence\Doctrine\Type\AlgaeIdType;
use App\Infrastructure\Persistence\Doctrine\Type\AquariumIdType;
use App\Infrastructure\Persistence\Doctrine\Type\SpeciesType;
use App\Infrastructure\Persistence\Doctrine\Type\SexType;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot(): void
    {
        parent::boot();

        if (!Type::hasType(FishIdType::NAME)) {
            Type::addType(FishIdType::NAME, FishIdType::class);
        }
        if (!Type::hasType(AlgaeIdType::NAME)) {
            Type::addType(AlgaeIdType::NAME, AlgaeIdType::class);
        }
        if (!Type::hasType(AquariumIdType::NAME)) {
            Type::addType(AquariumIdType::NAME, AquariumIdType::class);
        }
        if (!Type::hasType(SpeciesType::NAME)) {
            Type::addType(SpeciesType::NAME, SpeciesType::class);
        }
        if (!Type::hasType(SexType::NAME)) {
            Type::addType(SexType::NAME, SexType::class);
        }
    }
}
```

---

## Base de données

### Schéma

**Tables principales** :

```sql
-- aquariums
CREATE TABLE aquariums (
    id UUID PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    current_turn INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);

-- fishes
CREATE TABLE fishes (
    id UUID PRIMARY KEY,
    aquarium_id UUID NOT NULL REFERENCES aquariums(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    species VARCHAR(50) NOT NULL,
    sex VARCHAR(10) NOT NULL,
    age INT NOT NULL DEFAULT 0,
    health_points INT NOT NULL DEFAULT 10,
    created_at TIMESTAMP NOT NULL,
    CONSTRAINT fk_fish_aquarium FOREIGN KEY (aquarium_id) REFERENCES aquariums(id)
);

-- algae
CREATE TABLE algae (
    id UUID PRIMARY KEY,
    aquarium_id UUID NOT NULL REFERENCES aquariums(id) ON DELETE CASCADE,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL DEFAULT 0,
    health_points INT NOT NULL DEFAULT 10,
    created_at TIMESTAMP NOT NULL,
    CONSTRAINT fk_algae_aquarium FOREIGN KEY (aquarium_id) REFERENCES aquariums(id)
);

-- game_events
CREATE TABLE game_events (
    id UUID PRIMARY KEY,
    aquarium_id UUID NOT NULL REFERENCES aquariums(id) ON DELETE CASCADE,
    turn_number INT NOT NULL,
    event_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL,
    CONSTRAINT fk_event_aquarium FOREIGN KEY (aquarium_id) REFERENCES aquariums(id)
);

-- Indexes
CREATE INDEX idx_fishes_aquarium ON fishes(aquarium_id);
CREATE INDEX idx_algae_aquarium ON algae(aquarium_id);
CREATE INDEX idx_events_aquarium_turn ON game_events(aquarium_id, turn_number);
```

### Mapping Doctrine avec attributs

**Exemple Fish** :

```php
declare(strict_types=1);

namespace App\Domain\Fish\Entity;

use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\HealthPoints;
use App\Domain\Shared\ValueObject\EntityName;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'fishes')]
final class Fish
{
    #[ORM\Id]
    #[ORM\Column(type: 'fish_id')]
    private FishId $id;

    #[ORM\Column(type: 'string', length: 100)]
    private EntityName $name;

    #[ORM\Column(type: 'species')]
    private Species $species;

    #[ORM\Column(type: 'sex')]
    private Sex $sex;

    #[ORM\Column(type: 'integer')]
    private Age $age;

    #[ORM\Column(name: 'health_points', type: 'integer')]
    private HealthPoints $healthPoints;

    // Constructor, methods...
}
```

---

## Tests

### Organisation

```
tests/
├── Domain/
│   ├── Fish/
│   │   ├── Entity/
│   │   │   └── FishTest.php
│   │   ├── ValueObject/
│   │   │   ├── SpeciesTest.php
│   │   │   └── SexTest.php
│   │   └── Strategy/
│   │       ├── FixedSexBehaviorTest.php
│   │       ├── ProtandricHermaphroditeBehaviorTest.php
│   │       └── OpportunisticHermaphroditeBehaviorTest.php
│   ├── Shared/
│   │   ├── ValueObject/
│   │   │   ├── AgeTest.php
│   │   │   └── HealthPointsTest.php
│   │   └── GameRulesTest.php
│   └── Service/
│       ├── FeedingServiceTest.php
│       ├── ReproductionServiceTest.php
│       └── TurnOrchestratorTest.php
│
├── Application/
│   └── UseCase/
│       ├── AddFishToAquarium/
│       │   └── AddFishHandlerTest.php
│       └── AdvanceTurn/
│           └── AdvanceTurnHandlerTest.php
│
├── Infrastructure/
│   └── Persistence/
│       └── Doctrine/
│           └── Repository/
│               ├── DoctrineFishRepositoryTest.php
│               └── DoctrineAquariumRepositoryTest.php
│
└── Presentation/
    └── Api/
        └── Controller/
            ├── AquariumControllerTest.php
            └── FishControllerTest.php
```

### Types de tests

#### Tests unitaires (Domain)

**Caractéristiques** :
- Pas de dépendances externes
- Très rapides
- Isolés
- Test de la logique métier pure

**Exemple** :

```php
declare(strict_types=1);

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\HealthPoints;
use App\Domain\Shared\Exception\InvalidHealthPointsException;
use App\Domain\Shared\GameRules;
use PHPUnit\Framework\TestCase;

final class HealthPointsTest extends TestCase
{
    public function test_it_creates_valid_health_points(): void
    {
        $hp = HealthPoints::fromInt(5);

        self::assertEquals(5, $hp->toInt());
    }

    public function test_it_creates_initial_health_points(): void
    {
        $hp = HealthPoints::initial();

        self::assertEquals(GameRules::INITIAL_HP, $hp->toInt());
    }

    public function test_it_throws_exception_for_negative_value(): void
    {
        $this->expectException(InvalidHealthPointsException::class);

        HealthPoints::fromInt(-1);
    }

    public function test_it_throws_exception_for_value_above_max(): void
    {
        $this->expectException(InvalidHealthPointsException::class);

        HealthPoints::fromInt(11);
    }

    public function test_it_adds_health_points(): void
    {
        $hp = HealthPoints::fromInt(5);
        $newHp = $hp->add(3);

        self::assertEquals(8, $newHp->toInt());
    }

    public function test_it_caps_at_max_when_adding(): void
    {
        $hp = HealthPoints::fromInt(9);
        $newHp = $hp->add(5);

        self::assertEquals(GameRules::MAX_HP, $newHp->toInt());
    }

    public function test_it_subtracts_health_points(): void
    {
        $hp = HealthPoints::fromInt(8);
        $newHp = $hp->subtract(3);

        self::assertEquals(5, $newHp->toInt());
    }

    public function test_it_floors_at_zero_when_subtracting(): void
    {
        $hp = HealthPoints::fromInt(2);
        $newHp = $hp->subtract(5);

        self::assertEquals(0, $newHp->toInt());
    }

    public function test_it_detects_hunger(): void
    {
        $hungryHp = HealthPoints::fromInt(5);
        $notHungryHp = HealthPoints::fromInt(6);

        self::assertTrue($hungryHp->isHungry());
        self::assertFalse($notHungryHp->isHungry());
    }

    public function test_it_detects_death(): void
    {
        $deadHp = HealthPoints::fromInt(0);
        $aliveHp = HealthPoints::fromInt(1);

        self::assertTrue($deadHp->isDead());
        self::assertFalse($aliveHp->isDead());
    }

    public function test_it_compares_equality(): void
    {
        $hp1 = HealthPoints::fromInt(5);
        $hp2 = HealthPoints::fromInt(5);
        $hp3 = HealthPoints::fromInt(3);

        self::assertTrue($hp1->equals($hp2));
        self::assertFalse($hp1->equals($hp3));
    }
}
```

#### Tests d'intégration (Infrastructure)

**Caractéristiques** :
- Utilisent la base de données de test
- Testent les repositories Doctrine
- Transactions rollback après chaque test

**Exemple** :

```php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Shared\ValueObject\EntityName;
use App\Infrastructure\Persistence\Doctrine\Repository\DoctrineFishRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineFishRepositoryTest extends KernelTestCase
{
    private DoctrineFishRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = self::getContainer()->get(DoctrineFishRepository::class);
    }

    public function test_it_saves_and_retrieves_fish(): void
    {
        $fish = Fish::create(
            FishId::generate(),
            EntityName::fromString('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE
        );

        $this->repository->save($fish);
        $retrieved = $this->repository->findById($fish->getId());

        self::assertNotNull($retrieved);
        self::assertTrue($fish->getId()->equals($retrieved->getId()));
        self::assertEquals('Nemo', $retrieved->getName()->toString());
    }
}
```

#### Tests fonctionnels (Presentation)

**Caractéristiques** :
- Testent l'API de bout en bout
- Simulent des requêtes HTTP
- Vérifient les réponses JSON

**Exemple** :

```php
declare(strict_types=1);

namespace App\Tests\Presentation\Api\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class AquariumControllerTest extends WebTestCase
{
    public function test_it_creates_an_aquarium(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/aquariums',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Mon Aquarium'])
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertArrayHasKey('aquariumId', $data);
    }

    public function test_it_adds_fish_to_aquarium(): void
    {
        $client = static::createClient();

        // Create aquarium first
        $client->request(
            'POST',
            '/api/aquariums',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test'])
        );

        $aquariumData = json_decode($client->getResponse()->getContent(), true);
        $aquariumId = $aquariumData['aquariumId'];

        // Add fish
        $client->request(
            'POST',
            "/api/aquariums/{$aquariumId}/fishes",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Nemo',
                'species' => 'clownfish',
                'sex' => 'male'
            ])
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}
```

### Configuration PHPUnit

**phpunit.xml.dist** :

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="tests/bootstrap.php"
         colors="true"
         executionOrder="random"
         failOnRisky="true"
         failOnWarning="true">
    <php>
        <ini name="display_errors" value="1"/>
        <ini name="error_reporting" value="-1"/>
        <server name="APP_ENV" value="test" force="true"/>
        <server name="SHELL_VERBOSITY" value="-1"/>
        <server name="SYMFONY_PHPUNIT_REMOVE" value=""/>
        <server name="SYMFONY_PHPUNIT_VERSION" value="11.0"/>
    </php>

    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Domain</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Infrastructure</directory>
            <directory>tests/Application</directory>
        </testsuite>
        <testsuite name="Functional">
            <directory>tests/Presentation</directory>
        </testsuite>
    </testsuites>

    <coverage includeUncoveredFiles="true"
              pathCoverage="false"
              ignoreDeprecatedCodeUnits="true"
              disableCodeCoverageIgnore="true">
        <include>
            <directory suffix=".php">src</directory>
        </include>
        <exclude>
            <directory>src/Kernel.php</directory>
            <directory>src/Infrastructure/Persistence/Doctrine/Type</directory>
        </exclude>
    </coverage>
</phpunit>
```

### Commandes de tests

```bash
# Tous les tests
php bin/phpunit

# Tests unitaires uniquement
php bin/phpunit --testsuite=Unit

# Tests d'intégration
php bin/phpunit --testsuite=Integration

# Tests fonctionnels
php bin/phpunit --testsuite=Functional

# Avec couverture
php bin/phpunit --coverage-html coverage
```

---

## Implémentation progressive

### Phase 1 : Configuration de base (Domain minimum)

**Objectif** : Créer et afficher l'aquarium avec poissons/algues

**Domain à implémenter** :
1. ✅ `GameRules` (constantes INITIAL_HP, MAX_HP)
2. ✅ `EntityId`, `FishId`, `AlgaeId`, `AquariumId`
3. ✅ `EntityName`
4. ✅ `Age` (simple stockage)
5. ✅ `HealthPoints` (validation 0-10)
6. ✅ `Sex` (Enum)
7. ✅ `Species` (Enum avec nom uniquement)
8. ✅ `Fish` (Entity)
9. ✅ `Algae` (Entity)
10. ✅ `TurnNumber`
11. ✅ `Aquarium` (Aggregate avec addFish, addAlgae, getFishes, getAlgae)

**Application** :
- `CreateAquariumHandler`
- `AddFishHandler`
- `AddAlgaeHandler`
- `GetAquariumStateHandler`

**Infrastructure** :
- Repositories Doctrine
- Types Doctrine custom
- Migrations

**Presentation** :
- API REST endpoints

**Tests** : Tous les Value Objects + Entities

---

### Phase 2 : Mécanique d'alimentation

**Objectif** : Faim + alimentation aléatoire

**Domain à ajouter** :
1. ✅ `Diet` (Enum)
2. ✅ Mise à jour `Species.getDiet()`
3. ✅ `RandomGeneratorInterface`
4. ✅ `HungerService`
5. ✅ `FeedingService`
6. ✅ Mise à jour `Aquarium.advanceTurn()`

**Application** :
- `AdvanceTurnHandler`

**Infrastructure** :
- `SymfonyRandomGenerator`

**Presentation** :
- POST /aquariums/{id}/turn

**Tests** : Services + comportements d'alimentation

---

### Phase 3 : Cycle de vie complet

**Objectif** : Vieillissement, mort, reproduction, croissance algues

**Domain à ajouter** :
1. ✅ Mise à jour `GameRules` (toutes constantes)
2. ✅ `SexualityBehaviorInterface` + implémentations
3. ✅ Mise à jour `Species.getSexualityBehavior()`
4. ✅ `AgingService`
5. ✅ `DeathService`
6. ✅ `ReproductionService`
7. ✅ `AlgaeGrowthService`
8. ✅ `NameGeneratorInterface`
9. ✅ Mise à jour `Aquarium.advanceTurn()` (orchestration complète)

**Infrastructure** :
- `FakerNameGenerator`

**Tests** : Tous les services + stratégies + orchestration

---

### Phase 4 : Persistance et historique

**Objectif** : Events, logs, historique

**Domain à ajouter** :
1. ✅ `GameEvent` (Entity)
2. ✅ `EventId`
3. ✅ `EventType` (Enum)
4. ✅ Mise à jour `Aquarium` (collection events)
5. ✅ `EventRepositoryInterface`

**Application** :
- `GetEventHistoryHandler`

**Infrastructure** :
- `DoctrineEventRepository`

**Presentation** :
- GET /aquariums/{id}/events

**Tests** : Events + repository

---

## Dépendances Composer

### Production

```json
{
    "require": {
        "php": ">=8.4",
        "symfony/uid": "7.3.*",
        "symfony/framework-bundle": "7.3.*",
        "symfony/console": "7.3.*",
        "symfony/validator": "7.3.*",
        "doctrine/orm": "^3.5",
        "doctrine/doctrine-bundle": "^2.18",
        "doctrine/doctrine-migrations-bundle": "^3.5",
        "fakerphp/faker": "^1.23"
    }
}
```

### Développement

```json
{
    "require-dev": {
        "phpunit/phpunit": "^12.4",
        "phpstan/phpstan": "^2.1",
        "symfony/browser-kit": "7.3.*",
        "symfony/css-selector": "7.3.*",
        "symfony/maker-bundle": "^1.0",
        "symfony/web-profiler-bundle": "7.3.*"
    }
}
```

---

## Checklist de démarrage

### Avant de commencer Phase 1

- [ ] Installer Faker : `composer require fakerphp/faker`
- [ ] Configurer `services.yaml` (autowiring)
- [ ] Configurer `doctrine.yaml` (auto_mapping)
- [ ] Configurer `phpunit.xml.dist`
- [ ] Créer la base de données de test

### Workflow TDD pour chaque composant

1. **Red** : Écrire le test qui échoue
2. **Green** : Écrire le code minimal pour passer le test
3. **Refactor** : Améliorer le code
4. **Commit** : Commit atomique avec message clair

### Convention de commits

```
feat: add HealthPoints value object with validation
test: add unit tests for HealthPoints
refactor: extract GameRules constants
fix: correct hunger threshold calculation
docs: update ARCHITECTURE_TECHNIQUE.md
```

---

**Version** : 1.0
**Dernière mise à jour** : 2025-01-06
**Statut** : Prêt pour implémentation Phase 1
