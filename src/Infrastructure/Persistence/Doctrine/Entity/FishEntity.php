<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use App\Infrastructure\Persistence\Doctrine\Type\AgeType;
use App\Infrastructure\Persistence\Doctrine\Type\EntityNameType;
use App\Infrastructure\Persistence\Doctrine\Type\FishIdType;
use App\Infrastructure\Persistence\Doctrine\Type\HealthPointsType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'fish')]
class FishEntity
{
    #[ORM\Id]
    #[ORM\Column(type: FishIdType::TYPE_NAME)]
    private FishId $id;

    #[ORM\Column(type: EntityNameType::TYPE_NAME)]
    private EntityName $name;

    #[ORM\Column(type: Types::ENUM, length: 50)]
    private Species $species;

    #[ORM\Column(type: Types::ENUM, length: 10)]
    private Sex $sex;

    #[ORM\Column(type: AgeType::TYPE_NAME)]
    private Age $age;

    #[ORM\Column(type: HealthPointsType::TYPE_NAME)]
    private HealthPoints $healthPoints;

    #[ORM\ManyToOne(targetEntity: AquariumEntity::class, inversedBy: 'fishes')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?AquariumEntity $aquarium = null;

    public function getId(): FishId
    {
        return $this->id;
    }

    public function setId(FishId $id): void
    {
        $this->id = $id;
    }

    public function getName(): EntityName
    {
        return $this->name;
    }

    public function getSpecies(): Species
    {
        return $this->species;
    }

    public function setSpecies(Species $species): void
    {
        $this->species = $species;
    }

    public function getSex(): Sex
    {
        return $this->sex;
    }

    public function setSex(Sex $sex): void
    {
        $this->sex = $sex;
    }

    public function getAge(): Age
    {
        return $this->age;
    }

    public function getHealthPoints(): HealthPoints
    {
        return $this->healthPoints;
    }

    public function setName(EntityName $name): void
    {
        $this->name = $name;
    }

    public function setAge(Age $age): void
    {
        $this->age = $age;
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
