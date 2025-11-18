<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Entity;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Shared\ValueObject\EntityName;
use App\Infrastructure\Persistence\Doctrine\Transformer\AquariumTransformer;
use App\Infrastructure\Persistence\Doctrine\Type\AquariumIdType;
use App\Infrastructure\Persistence\Doctrine\Type\EntityNameType;
use App\Infrastructure\Persistence\Doctrine\Type\TurnNumberType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[ORM\Entity]
#[ORM\Table(name: 'aquarium')]
#[Map(target: Aquarium::class, source: Aquarium::class, transform: [AquariumTransformer::class, 'transform'])]
class AquariumEntity
{
    /** @var Collection<int, FishEntity>|null */
    #[ORM\OneToMany(
        targetEntity: FishEntity::class,
        mappedBy: 'aquarium',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Map(if: false)]
    private ?Collection $fishes = null;

    /** @var Collection<int, AlgaeEntity>|null */
    #[ORM\OneToMany(
        targetEntity: AlgaeEntity::class,
        mappedBy: 'aquarium',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    #[Map(if: false)]
    private ?Collection $algae = null;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(type: AquariumIdType::TYPE_NAME)]
        #[Map(if: false)]
        private AquariumId $id,
        #[ORM\Column(type: EntityNameType::TYPE_NAME)]
        #[Map( if: false)]
        private EntityName $name,
        #[ORM\Column(type: TurnNumberType::TYPE_NAME)]
        #[Map(if: false)]
        private TurnNumber $turnNumber
    ) {
        $this->fishes = new ArrayCollection();
        $this->algae = new ArrayCollection();
    }

    public function getId(): AquariumId
    {
        return $this->id;
    }

    public function setId(AquariumId $id): void
    {
        $this->id = $id;
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
     * @return Collection<int, FishEntity>
     */
    public function getFishes(): Collection
    {
        assert($this->fishes instanceof Collection);
        return $this->fishes;
    }

    public function initFishes(bool $force): void
    {
        if (!$force && $this->fishes instanceof Collection && $this->fishes->count() > 0) {
            return;
        }

        $this->fishes = new ArrayCollection();
    }

    public function addFish(FishEntity $fish): void
    {
        assert($this->fishes instanceof Collection);
        if (!$this->fishes->contains($fish)) {
            $this->fishes->add($fish);
        }
    }

    public function removeFish(FishEntity $fish): void
    {
        assert($this->fishes instanceof Collection);
        $this->fishes->removeElement($fish);
    }

    /**
     * @return Collection<int, AlgaeEntity>
     */
    public function getAlgae(): Collection
    {
        assert($this->algae instanceof Collection);
        return $this->algae;
    }

    public function initAlgae(bool $force): void
    {
        if (!$force && $this->algae instanceof Collection && $this->algae->count() > 0) {
            return;
        }

        $this->algae = new ArrayCollection();
    }

    public function addAlgae(AlgaeEntity $algae): void
    {
        assert($this->algae instanceof Collection);
        if (!$this->algae->contains($algae)) {
            $this->algae->add($algae);
        }
    }

    public function removeAlgae(AlgaeEntity $algae): void
    {
        assert($this->algae instanceof Collection);
        $this->algae->removeElement($algae);
    }

    public function setName(EntityName $name): void
    {
        $this->name = $name;
    }

    public function setTurnNumber(TurnNumber $turnNumber): void
    {
        $this->turnNumber = $turnNumber;
    }
}
