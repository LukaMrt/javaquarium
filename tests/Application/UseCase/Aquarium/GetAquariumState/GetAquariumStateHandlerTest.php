<?php

declare(strict_types=1);

namespace App\Tests\Application\UseCase\Aquarium\GetAquariumState;

use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use App\Application\Exception\AquariumNotFoundException;
use App\Application\UseCase\Aquarium\GetAquariumState\GetAquariumStateHandler;
use App\Application\UseCase\Aquarium\GetAquariumState\GetAquariumStateQuery;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Domain\Aquarium\Repository\AquariumRepositoryInterface;
use App\Domain\Aquarium\ValueObject\AquariumId;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class GetAquariumStateHandlerTest extends KernelTestCase
{
    private GetAquariumStateHandler $handler;

    protected function setUp(): void
    {
        self::bootKernel();
        $repository = self::getContainer()->get(AquariumRepositoryInterface::class);
        $mapper = self::getContainer()->get(ObjectMapperInterface::class);

        $this->assertInstanceOf(AquariumRepositoryInterface::class, $repository);
        $this->assertInstanceOf(ObjectMapperInterface::class, $mapper);

        $this->handler = new GetAquariumStateHandler($repository, $mapper);
    }

    public function testItReturnsAquariumState(): void
    {
        // Given
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::TROPICAL_REEF);
        $query = new GetAquariumStateQuery($aquarium->getId()->toString());

        // When
        $response = $this->handler->handle($query);

        // Then
        $this->assertSame($aquarium->getId()->toString(), $response->aquariumId);
        $this->assertSame('Récif Tropical', $response->name);
        $this->assertSame(5, $response->turnNumber);
        $this->assertCount(3, $response->fishes);
        $this->assertCount(2, $response->algae);
    }

    public function testItThrowsExceptionWhenAquariumNotFound(): void
    {
        // Given
        $nonExistentId = AquariumId::generate()->toString();
        $query = new GetAquariumStateQuery($nonExistentId);

        // Then
        $this->expectException(AquariumNotFoundException::class);

        // When
        $this->handler->handle($query);
    }
}
