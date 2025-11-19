<?php

declare(strict_types=1);

namespace App\Domain\Algae\Entity;

use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;

final class Algae
{
    public function __construct(
        private readonly AlgaeId $id,
        private readonly EntityName $name,
        private Age $age,
        private HealthPoints $healthPoints
    ) {
    }

    public function getId(): AlgaeId
    {
        return $this->id;
    }

    public function getName(): EntityName
    {
        return $this->name;
    }

    public function getAge(): Age
    {
        return $this->age;
    }

    public function getHealthPoints(): HealthPoints
    {
        return $this->healthPoints;
    }

    public function loseHealth(int $amount): void
    {
        $this->healthPoints = $this->healthPoints->subtract($amount);
    }

    public function gainHealth(int $amount): void
    {
        $this->healthPoints = $this->healthPoints->add($amount);
    }

    public function age(): void
    {
        $this->age = $this->age->increment();
    }

    public function isDead(): bool
    {
        return $this->healthPoints->isDead();
    }

    /**
     * Makes the algae grow and potentially split.
     * Returns the new offspring algae if split occurred, null otherwise.
     */
    public function grow(): ?self
    {
        // 1. Natural growth
        $this->gainHealth(GameRules::ALGAE_GROWTH_HP);

        // 2. Check for split
        if ($this->healthPoints->toInt() >= GameRules::ALGAE_SPLIT_THRESHOLD) {
            return $this->split();
        }

        return null;
    }

    private function split(): self
    {
        $currentHp = $this->healthPoints->toInt();
        $halfHp = (int) floor($currentHp / 2);

        // Parent loses half HP
        $this->loseHealth($halfHp);

        // Create offspring
        return new self(
            AlgaeId::generate(),
            $this->name,
            Age::initial(),
            new HealthPoints($halfHp)
        );
    }
}
