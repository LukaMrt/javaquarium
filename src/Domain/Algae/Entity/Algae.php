<?php

declare(strict_types=1);

namespace App\Domain\Algae\Entity;

use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;

final readonly class Algae
{
    public function __construct(
        private AlgaeId $id,
        private EntityName $name,
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
}
