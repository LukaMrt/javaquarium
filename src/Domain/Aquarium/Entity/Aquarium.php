<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\Entity;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Service\FeedingService;
use App\Domain\Service\HungerService;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\ValueObject\EntityName;

final class Aquarium
{
    private readonly HungerService $hungerService;
    private readonly FeedingService $feedingService;

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
        $this->hungerService = new HungerService();
        $this->feedingService = new FeedingService();
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
        // Phase 1: Age all entities
        foreach ($this->fishes as $fish) {
            $fish->age();
        }
        foreach ($this->algae as $algae) {
            $algae->age();
        }

        // Phase 2: Apply hunger to all fish
        foreach ($this->fishes as $fish) {
            $this->hungerService->applyHunger($fish);
        }

        // Phase 3: Feeding - hungry fish attempt to eat
        foreach ($this->fishes as $fish) {
            if ($this->hungerService->isHungry($fish)) {
                $this->attemptFeeding($fish, $randomGenerator);
            }
        }

        // Phase 4: Remove dead entities
        $this->removeDead();

        // Phase 5: Increment turn number
        $this->turnNumber = $this->turnNumber->increment();
    }

    private function attemptFeeding(Fish $fish, RandomGeneratorInterface $randomGenerator): void
    {
        $diet = $fish->getSpecies()->getDiet();

        // Build list of potential targets
        $potentialTargets = [];

        if ($diet->isHerbivorous()) {
            // Herbivores can eat algae
            $potentialTargets = array_filter($this->algae, fn(Algae $algae) => !$algae->isDead());
        } elseif ($diet->isCarnivorous()) {
            // Carnivores can eat other fish (not same species, not self)
            $potentialTargets = array_filter(
                $this->fishes,
                fn(Fish $otherFish) => $this->feedingService->canFeed($fish, $otherFish)
            );
        }

        // If targets available, randomly select one and attempt to feed
        if (!empty($potentialTargets)) {
            $target = $randomGenerator->selectRandom(array_values($potentialTargets));
            $this->feedingService->feed($fish, $target);
        }
    }

    private function removeDead(): void
    {
        $this->fishes = array_values(array_filter($this->fishes, fn(Fish $fish) => !$fish->isDead()));
        $this->algae = array_values(array_filter($this->algae, fn(Algae $algae) => !$algae->isDead()));
    }
}
