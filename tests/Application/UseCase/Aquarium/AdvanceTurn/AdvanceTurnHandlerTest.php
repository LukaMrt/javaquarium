<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\Aquarium\AdvanceTurn;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Application\UseCase\Aquarium\AdvanceTurn\AdvanceTurnCommand;
use App\Application\UseCase\Aquarium\AdvanceTurn\AdvanceTurnHandler;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Service\RandomGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AdvanceTurnHandlerTest extends KernelTestCase
{
    private AdvanceTurnHandler $handler;

    private AquariumRepositoryInterface $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get(AquariumRepositoryInterface::class);
        $mapper = self::getContainer()->get(ObjectMapperInterface::class);
        $randomGenerator = self::getContainer()->get(RandomGeneratorInterface::class);

        $this->assertInstanceOf(AquariumRepositoryInterface::class, $repository);
        $this->assertInstanceOf(ObjectMapperInterface::class, $mapper);
        $this->assertInstanceOf(RandomGeneratorInterface::class, $randomGenerator);

        $this->repository = $repository;
        $this->handler = new AdvanceTurnHandler($repository, $mapper, $randomGenerator);
    }

    public function testItAdvancesTurn(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::TROPICAL_REEF);
        $initialTurn = $aquarium->getTurnNumber()->toInt();
        $command = new AdvanceTurnCommand($aquarium->getId()->toString());

        // When
        $response = $this->handler->handle($command);

        // Then
        $this->assertSame($aquarium->getId()->toString(), $response->aquariumId);
        $this->assertSame($initialTurn + 1, $response->turnNumber);

        $updatedAquarium = $this->repository->findById($aquarium->getId());
        $this->assertInstanceOf(Aquarium::class, $updatedAquarium);
        $this->assertSame($initialTurn + 1, $updatedAquarium->getTurnNumber()->toInt());
    }

    public function testItThrowsExceptionWhenAquariumNotFound(): void
    {
        // Given
        $nonExistentId = AquariumId::generate()->toString();
        $command = new AdvanceTurnCommand($nonExistentId);

        // Then
        $this->expectException(AquariumNotFoundException::class);

        // When
        $this->handler->handle($command);
    }
}
