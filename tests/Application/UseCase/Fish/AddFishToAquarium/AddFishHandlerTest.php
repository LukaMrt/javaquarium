<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\Fish\AddFishToAquarium;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Application\Exception\AquariumNotFoundException;
use App\Application\UseCase\Fish\AddFishToAquarium\AddFishCommand;
use App\Application\UseCase\Fish\AddFishToAquarium\AddFishHandler;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AddFishHandlerTest extends KernelTestCase
{
    private AddFishHandler $handler;

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
        $this->handler = new AddFishHandler($repository, $mapper, $validator);
    }

    public function testItAddsFishToAquarium(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::TROPICAL_REEF);
        $fishId = FishId::generate()->toString();
        $command = new AddFishCommand(
            $aquarium->getId()->toString(),
            $fishId,
            'Nemo',
            Species::CLOWNFISH,
            Sex::MALE
        );
        $initialFishCount = count($aquarium->getFishes());

        // When
        $response = $this->handler->handle($command);

        // Then
        $this->assertSame($fishId, $response->fishId);

        $updatedAquarium = $this->repository->findById($aquarium->getId());
        $this->assertInstanceOf(Aquarium::class, $updatedAquarium);
        $this->assertCount($initialFishCount + 1, $updatedAquarium->getFishes());

        $addedFish = $updatedAquarium->getFishes()[$initialFishCount];
        $this->assertSame('Nemo', $addedFish->getName()->toString());
        $this->assertSame(Species::CLOWNFISH, $addedFish->getSpecies());
        $this->assertSame(Sex::MALE, $addedFish->getSex());
        $this->assertSame(0, $addedFish->getAge()->toInt());
        $this->assertSame(10, $addedFish->getHealthPoints()->toInt());
    }

    public function testItThrowsExceptionWhenAquariumNotFound(): void
    {
        // Given
        $nonExistentId = AquariumId::generate()->toString();
        $command = new AddFishCommand(
            $nonExistentId,
            FishId::generate()->toString(),
            'Dory',
            Species::SOLE,
            Sex::FEMALE
        );

        // Then
        $this->expectException(AquariumNotFoundException::class);

        // When
        $this->handler->handle($command);
    }
}
