<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\Algae\AddAlgaeToAquarium;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Application\UseCase\Algae\AddAlgaeToAquarium\AddAlgaeCommand;
use App\Application\UseCase\Algae\AddAlgaeToAquarium\AddAlgaeHandler;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AddAlgaeHandlerTest extends KernelTestCase
{
    private AddAlgaeHandler $handler;

    private AquariumRepositoryInterface $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get(AquariumRepositoryInterface::class);
        $mapper = self::getContainer()->get(ObjectMapperInterface::class);

        $this->assertInstanceOf(AquariumRepositoryInterface::class, $repository);
        $this->assertInstanceOf(ObjectMapperInterface::class, $mapper);

        $this->repository = $repository;
        $this->handler = new AddAlgaeHandler($repository, $mapper);
    }

    public function testItAddsAlgaeToAquarium(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::TROPICAL_REEF);
        $algaeId = AlgaeId::generate()->toString();
        $command = new AddAlgaeCommand(
            $aquarium->getId()->toString(),
            $algaeId,
            'Algue verte'
        );
        $initialAlgaeCount = count($aquarium->getAlgae());

        // When
        $response = $this->handler->handle($command);

        // Then
        $this->assertSame($algaeId, $response->algaeId);

        $updatedAquarium = $this->repository->findById($aquarium->getId());
        $this->assertInstanceOf(Aquarium::class, $updatedAquarium);
        $this->assertCount($initialAlgaeCount + 1, $updatedAquarium->getAlgae());

        $addedAlgae = $updatedAquarium->getAlgae()[$initialAlgaeCount];
        $this->assertSame('Algue verte', $addedAlgae->getName()->toString());
        $this->assertSame(0, $addedAlgae->getAge()->toInt());
        $this->assertSame(10, $addedAlgae->getHealthPoints()->toInt());
    }

    public function testItThrowsExceptionWhenAquariumNotFound(): void
    {
        // Given
        $nonExistentId = AquariumId::generate()->toString();
        $command = new AddAlgaeCommand(
            $nonExistentId,
            AlgaeId::generate()->toString(),
            'Algue brune'
        );

        // Then
        $this->expectException(AquariumNotFoundException::class);

        // When
        $this->handler->handle($command);
    }
}
