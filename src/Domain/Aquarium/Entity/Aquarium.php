<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\Entity;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\EntityName;

final class Aquarium
{
    /**
     * @param Fish[] $fishes
     * @param Algae[] $algae
     */
    public function __construct(
        private readonly AquariumId $id,
        private readonly EntityName $name,
        private TurnNumber $turnNumber,
        private array $fishes = [],
        private array $algae = [],
    ) {
    }

    public function addFish(Fish $fish): void
    {
        $this->fishes[] = $fish;
    }

    public function addAlgae(Algae $algae): void
    {
        $this->algae[] = $algae;
    }

    public function getId(): AquariumId
    {
        return $this->id;
    }

    public function getName(): EntityName
    {
        return $this->name;
    }

    public function getTurnNumber(): TurnNumber
    {
        return $this->turnNumber;
    }

    /**
     * @return Fish[]
     */
    public function getFishes(): array
    {
        return $this->fishes;
    }

    /**
     * @return Algae[]
     */
    public function getAlgae(): array
    {
        return $this->algae;
    }

    public function advanceTurn(RandomGeneratorInterface $randomGenerator): void
    {
        // Shuffle order to randomize processing
        $this->fishes = $randomGenerator->shuffle($this->fishes);
        $this->algae = $randomGenerator->shuffle($this->algae);

        // Phase 1: Age all entities
        foreach ($this->fishes as $fish) {
            $fish->age();
        }
        foreach ($this->algae as $algae) {
            $algae->age();
        }

        // Phase 2: Apply hunger to all fish
        foreach ($this->fishes as $fish) {
            $fish->loseHealth(GameRules::HP_LOSS_PER_TURN);
        }

        // Phase 3: Feeding - hungry fish attempt to eat
        foreach ($this->fishes as $fish) {
            if ($fish->isHungry()) {
                $this->attemptFeeding($fish, $randomGenerator);
            }
        }

        // Phase 4: Remove dead entities
        $this->removeDead();

        // Phase 5: Increment turn number
        $this->turnNumber = $this->turnNumber->increment();
    }

    private function attemptFeeding(Fish $predator, RandomGeneratorInterface $randomGenerator): void
    {
        $diet = $predator->getSpecies()->getDiet();

        // Build list of valid targets
        $validTargets = [];

        if ($diet->isHerbivorous()) {
            // Herbivores can eat algae
            $validTargets = array_filter($this->algae, fn(Algae $algae): bool => !$algae->isDead());
        } elseif ($diet->isCarnivorous()) {
            // Carnivores can eat other fish (not same species, not self)
            $validTargets = array_filter(
                $this->fishes,
                fn(Fish $prey): bool => !$prey->isDead()
                    && !$predator->getId()->equals($prey->getId())
                    && $predator->getSpecies() !== $prey->getSpecies()
            );
        }

        // If no valid targets, cannot feed
        if ($validTargets === []) {
            return;
        }

        // Select random target and feed
        $target = $randomGenerator->selectRandom(array_values($validTargets));

        if ($target instanceof Algae) {
            $predator->gainHealth(GameRules::HERBIVOROUS_HP_GAIN);
            $target->loseHealth(GameRules::ALGAE_HP_LOSS_WHEN_EATEN);
        } elseif ($target instanceof Fish) {
            $predator->gainHealth(GameRules::CARNIVOROUS_HP_GAIN);
            $target->loseHealth(GameRules::FISH_HP_LOSS_WHEN_ATTACKED);
        }
    }

    private function removeDead(): void
    {
        $this->fishes = array_values(array_filter($this->fishes, fn(Fish $fish): bool => !$fish->isDead()));
        $this->algae = array_values(array_filter($this->algae, fn(Algae $algae): bool => !$algae->isDead()));
    }
}
