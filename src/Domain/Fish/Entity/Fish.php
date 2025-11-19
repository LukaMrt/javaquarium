<?php

declare(strict_types=1);

namespace App\Domain\Fish\Entity;

use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;

final class Fish
{
    public function __construct(
        private readonly FishId $id,
        private readonly EntityName $name,
        private readonly Species $species,
        private readonly Sex $sex,
        private Age $age,
        private HealthPoints $healthPoints
    ) {
    }

    public function getId(): FishId
    {
        return $this->id;
    }

    public function getName(): EntityName
    {
        return $this->name;
    }

    public function getSpecies(): Species
    {
        return $this->species;
    }

    public function getSex(): Sex
    {
        return $this->sex;
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

    public function isHungry(): bool
    {
        return $this->healthPoints->isHungry();
    }

    public function isDead(): bool
    {
        return $this->healthPoints->isDead() || $this->age->toInt() >= GameRules::MAX_AGE;
    }
}
