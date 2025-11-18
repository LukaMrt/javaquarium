<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Transformer;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Fish\Entity\Fish;
use App\Infrastructure\Persistence\Doctrine\Entity\AlgaeEntity;
use App\Infrastructure\Persistence\Doctrine\Entity\AquariumEntity;
use App\Infrastructure\Persistence\Doctrine\Entity\FishEntity;

final readonly class AquariumTransformer
{
    public static function transform(mixed $value, object $source): AquariumEntity|Aquarium
    {
        if ($source instanceof Aquarium && $value instanceof AquariumEntity) {
            return self::toDoctrine($source, $value);
        }

        if ($source instanceof AquariumEntity && $value instanceof Aquarium) {
            return self::toDomain($source);
        }

        throw new \InvalidArgumentException(sprintf(
            'Unsupported transformation from %s to %s',
            get_debug_type($source),
            get_debug_type($value),
        ));
    }

    public static function toDoctrine(Aquarium $source, AquariumEntity $target): AquariumEntity
    {
        $target->setId($source->getId());
        $target->setName($source->getName());
        $target->setTurnNumber($source->getTurnNumber());

        $target->initFishes(false);
        $target->initAlgae(false);

        $existingFishes = [];
        foreach ($target->getFishes() as $existingFish) {
            $existingFishes[$existingFish->getId()->toString()] = $existingFish;
        }

        $existingAlgae = [];
        foreach ($target->getAlgae() as $existingAlga) {
            $existingAlgae[$existingAlga->getId()->toString()] = $existingAlga;
        }

        $target->initFishes(true);
        $target->initAlgae(true);

        foreach ($source->getFishes() as $fish) {
            $fishEntity = $existingFishes[$fish->getId()->toString()] ?? new FishEntity();
            $fishEntity->setId($fish->getId());
            $fishEntity->setName($fish->getName());
            $fishEntity->setSpecies($fish->getSpecies());
            $fishEntity->setSex($fish->getSex());
            $fishEntity->setAge($fish->getAge());
            $fishEntity->setHealthPoints($fish->getHealthPoints());
            $fishEntity->setAquarium($target);
            $target->addFish($fishEntity);
        }

        foreach ($source->getAlgae() as $algae) {
            $algaeEntity = $existingAlgae[$algae->getId()->toString()] ?? new AlgaeEntity();
            $algaeEntity->setId($algae->getId());
            $algaeEntity->setName($algae->getName());
            $algaeEntity->setAge($algae->getAge());
            $algaeEntity->setHealthPoints($algae->getHealthPoints());
            $algaeEntity->setAquarium($target);
            $target->addAlgae($algaeEntity);
        }

        return $target;
    }

    public static function toDomain(AquariumEntity $source): Aquarium
    {
        $fishes = [];
        foreach ($source->getFishes() as $fishEntity) {
            $fishes[] = new Fish(
                $fishEntity->getId(),
                $fishEntity->getName(),
                $fishEntity->getSpecies(),
                $fishEntity->getSex(),
                $fishEntity->getAge(),
                $fishEntity->getHealthPoints(),
            );
        }

        $algae = [];
        foreach ($source->getAlgae() as $algaeEntity) {
            $algae[] = new Algae(
                $algaeEntity->getId(),
                $algaeEntity->getName(),
                $algaeEntity->getAge(),
                $algaeEntity->getHealthPoints(),
            );
        }

        return new Aquarium(
            $source->getId(),
            $source->getName(),
            $source->getTurnNumber(),
            $fishes,
            $algae
        );
    }
}
