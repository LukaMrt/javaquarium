<?php

declare(strict_types=1);

namespace App\Domain\Fish\Entity;

use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;

final readonly class Fish
{
    public function __construct(
        private FishId $id,
        private EntityName $name,
        private Species $species,
        private Sex $sex,
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
}
