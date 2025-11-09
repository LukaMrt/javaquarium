<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\Entity;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
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
        private readonly TurnNumber $turnNumber,
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

    public function advanceTurn(): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->turnNumber->increment(),
            $this->fishes,
            $this->algae
        );
    }
}
