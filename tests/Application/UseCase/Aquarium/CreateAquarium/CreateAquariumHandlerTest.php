<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\Aquarium\CreateAquarium;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use App\Application\UseCase\Aquarium\CreateAquarium\CreateAquariumCommand;
use App\Application\UseCase\Aquarium\CreateAquarium\CreateAquariumHandler;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateAquariumHandlerTest extends KernelTestCase
{
    private CreateAquariumHandler $handler;

    private AquariumRepositoryInterface $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get(AquariumRepositoryInterface::class);
        $mapper = self::getContainer()->get(ObjectMapperInterface::class);
        $validator = self::getContainer()->get(ValidatorInterface::class);

        $this->assertInstanceOf(AquariumRepositoryInterface::class, $repository);
        $this->assertInstanceOf(ObjectMapperInterface::class, $mapper);
        $this->assertInstanceOf(ValidatorInterface::class, $validator);

        $this->repository = $repository;
        $this->handler = new CreateAquariumHandler($repository, $mapper, $validator);
    }

    public function testItCreatesNewAquarium(): void
    {
        // Given
        $aquariumId = AquariumId::generate()->toString();
        $command = new CreateAquariumCommand($aquariumId, 'Test Aquarium');

        // When
        $response = $this->handler->handle($command);

        // Then
        $this->assertSame($aquariumId, $response->aquariumId);

        $savedAquarium = $this->repository->findById(AquariumId::fromString($aquariumId));
        $this->assertInstanceOf(Aquarium::class, $savedAquarium);
        $this->assertSame('Test Aquarium', $savedAquarium->getName()->toString());
        $this->assertSame(0, $savedAquarium->getTurnNumber()->toInt());
    }

    public function testItReturnsExistingAquariumIfAlreadyExists(): void
    {
        // Given
        $aquariumId = AquariumId::generate()->toString();
        $command = new CreateAquariumCommand($aquariumId, 'First Name');
        $this->handler->handle($command);

        // When - Try to create again with different name
        $command2 = new CreateAquariumCommand($aquariumId, 'Second Name');
        $response = $this->handler->handle($command2);

        // Then - Returns existing aquarium ID, doesn't update name
        $this->assertSame($aquariumId, $response->aquariumId);

        $aquarium = $this->repository->findById(AquariumId::fromString($aquariumId));
        $this->assertInstanceOf(Aquarium::class, $aquarium);
        $this->assertSame('First Name', $aquarium->getName()->toString());
    }
}
