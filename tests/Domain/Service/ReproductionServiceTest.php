<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Service\MonosexualReproductionStrategy;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Service\ReproductionService;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class ReproductionServiceTest extends TestCase
{
    public function test_attemptReproduction_creates_offspring_when_valid_partner_found(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);
        $femaleCarp = $this->createFish(Sex::FEMALE, 3, Species::CARP);

        $aquarium->addFish($maleCarp);
        $aquarium->addFish($femaleCarp);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('selectRandom')->willReturn($femaleCarp);
        $randomGenerator->method('randomBoolean')->willReturn(true);

        $strategies = [new MonosexualReproductionStrategy()];
        $service = new ReproductionService($strategies);

        $this->assertCount(2, $aquarium->getFishes());

        $service->attemptReproduction($maleCarp, $aquarium, $randomGenerator);

        $this->assertCount(3, $aquarium->getFishes());
    }

    public function test_attemptReproduction_does_nothing_when_no_partner_found(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);

        $aquarium->addFish($maleCarp);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        $strategies = [new MonosexualReproductionStrategy()];
        $service = new ReproductionService($strategies);

        $this->assertCount(1, $aquarium->getFishes());

        $service->attemptReproduction($maleCarp, $aquarium, $randomGenerator);

        $this->assertCount(1, $aquarium->getFishes()); // No offspring
    }

    public function test_attemptReproduction_throws_when_no_strategy_found(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3, Species::CARP);

        $aquarium->addFish($maleCarp);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        $strategies = []; // No strategies
        $service = new ReproductionService($strategies);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No reproduction strategy found for sexual behavior: MONOSEXUAL');

        $service->attemptReproduction($maleCarp, $aquarium, $randomGenerator);
    }

    private function createFish(Sex $sex, int $age, Species $species): Fish
    {
        return new Fish(
            id: FishId::generate(),
            name: new EntityName('Test Fish'),
            species: $species,
            sex: $sex,
            age: new Age($age),
            healthPoints: new HealthPoints(10)
        );
    }

    private function createAquarium(): Aquarium
    {
        return new Aquarium(
            id: AquariumId::generate(),
            name: new EntityName('Test Aquarium'),
            turnNumber: TurnNumber::initial()
        );
    }
}
