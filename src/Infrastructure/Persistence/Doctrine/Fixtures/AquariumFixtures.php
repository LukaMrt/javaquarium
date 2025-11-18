<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Fixtures;

use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Infrastructure\Persistence\Doctrine\Entity\AquariumEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final class AquariumFixtures extends Fixture
{
    public function __construct(
        private readonly ObjectMapperInterface $objectMapper
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $domainAquariums = AquariumFixturesData::all();

        foreach ($domainAquariums as $domainAquarium) {
            $aquariumEntity = $this->objectMapper->map($domainAquarium, AquariumEntity::class);
            $manager->persist($aquariumEntity);
        }

        $manager->flush();
    }
}
