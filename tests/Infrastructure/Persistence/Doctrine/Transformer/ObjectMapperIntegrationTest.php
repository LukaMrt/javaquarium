<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Doctrine\Transformer;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Infrastructure\Persistence\Doctrine\Entity\AquariumEntity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

final class ObjectMapperIntegrationTest extends KernelTestCase
{
    private ObjectMapperInterface $mapper;

    protected function setUp(): void
    {
        $objectMapper = self::getContainer()->get(ObjectMapperInterface::class);
        $this->assertInstanceOf(ObjectMapperInterface::class, $objectMapper);
        $this->mapper = $objectMapper;
    }

    public function testMapAquarium(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::TROPICAL_REEF);

        // When
        $entity = $this->mapper->map($aquarium, AquariumEntity::class);
        $domain = $this->mapper->map($entity, Aquarium::class);

        // Then
        $this->assertInstanceOf(AquariumEntity::class, $entity);
        $this->assertEquals($aquarium, $domain);
    }
}
