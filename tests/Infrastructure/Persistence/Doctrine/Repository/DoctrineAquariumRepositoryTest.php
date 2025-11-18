<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Shared\ValueObject\EntityName;
use App\Infrastructure\Persistence\Doctrine\Repository\DoctrineAquariumRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineAquariumRepositoryTest extends KernelTestCase
{
    private AquariumRepositoryInterface $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get(DoctrineAquariumRepository::class);
        $this->assertInstanceOf(AquariumRepositoryInterface::class, $repository);
        $this->repository = $repository;
    }

    public function testFindById(): void
    {
        // Given
        $expectedAquarium = AquariumFixturesData::get(AquariumFixturesData::CARP_POND);

        // When
        $found = $this->repository->findById($expectedAquarium->getId());

        // Then
        $this->assertEquals($expectedAquarium, $found);
    }

    public function testSave(): void
    {
        // Given
        $expectedAquarium = AquariumFixturesData::get(AquariumFixturesData::BASS_POOL);
        $this->repository->delete($expectedAquarium);

        // When
        $this->repository->save($expectedAquarium);

        // Then
        $found = $this->repository->findById($expectedAquarium->getId());
        $this->assertEquals($expectedAquarium, $found);
    }

    public function testSaveShouldUpdate(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::GROUPER_TANK);
        $aquarium = new Aquarium(
            id: $aquarium->getId(),
            name: new EntityName('New name'),
            turnNumber: $aquarium->getTurnNumber(),
            fishes: $aquarium->getFishes(),
            algae: $aquarium->getAlgae(),
        );

        // When
        $this->repository->save($aquarium);

        // Then
        $found = $this->repository->findById($aquarium->getId());
        $this->assertEquals($aquarium, $found);
    }

    public function testFindByIdReturnsNullWhenNotFound(): void
    {
        // Given
        $nonExistentId = AquariumId::generate();

        // When
        $found = $this->repository->findById($nonExistentId);

        // Then
        $this->assertNotInstanceOf(Aquarium::class, $found);
    }

    public function testFindAllAquariums(): void
    {
        // When
        $allAquariums = $this->repository->findAllAquariums();

        // Then
        $this->assertCount(count(AquariumFixturesData::all()), $allAquariums);
    }

    public function testDelete(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::SOLE_ZONE);

        // When
        $this->repository->delete($aquarium);

        // Then
        $notFound = $this->repository->findById($aquarium->getId());
        $remainingAquariums = $this->repository->findAllAquariums();

        $this->assertNotInstanceOf(Aquarium::class, $notFound);
        $this->assertCount(count(AquariumFixturesData::all()) - 1, $remainingAquariums);
    }
}
