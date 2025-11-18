<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use App\Infrastructure\Persistence\Doctrine\Type\AgeType;
use App\Infrastructure\Persistence\Doctrine\Type\AlgaeIdType;
use App\Infrastructure\Persistence\Doctrine\Type\EntityNameType;
use App\Infrastructure\Persistence\Doctrine\Type\HealthPointsType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'algae')]
class AlgaeEntity
{
    #[ORM\Id]
    #[ORM\Column(type: AlgaeIdType::TYPE_NAME)]
    private AlgaeId $id;

    #[ORM\Column(type: EntityNameType::TYPE_NAME)]
    private EntityName $name;

    #[ORM\Column(type: AgeType::TYPE_NAME)]
    private Age $age;

    #[ORM\Column(type: HealthPointsType::TYPE_NAME)]
    private HealthPoints $healthPoints;

    #[ORM\ManyToOne(targetEntity: AquariumEntity::class, inversedBy: 'algae')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?AquariumEntity $aquarium = null;

    public function getId(): AlgaeId
    {
        return $this->id;
    }

    public function setId(AlgaeId $id): void
    {
        $this->id = $id;
    }

    public function getName(): EntityName
    {
        return $this->name;
    }

    public function setName(EntityName $name): void
    {
        $this->name = $name;
    }

    public function getAge(): Age
    {
        return $this->age;
    }

    public function setAge(Age $age): void
    {
        $this->age = $age;
    }

    public function getHealthPoints(): HealthPoints
    {
        return $this->healthPoints;
    }

    public function setHealthPoints(HealthPoints $healthPoints): void
    {
        $this->healthPoints = $healthPoints;
    }

    public function getAquarium(): ?AquariumEntity
    {
        return $this->aquarium;
    }

    public function setAquarium(?AquariumEntity $aquarium): void
    {
        $this->aquarium = $aquarium;
    }
}
