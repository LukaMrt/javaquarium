<?php

declare(strict_types=1);

namespace App\Tests\Domain\Action;

use App\Domain\Action\FishReproduceAction;
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

final class FishReproduceActionTest extends TestCase
{
    public function test_execute_does_nothing_when_fish_is_dead(): void
    {
        $aquarium = $this->createAquarium();
        $deadFish = $this->createFish(Sex::MALE, 3);
        $deadFish->loseHealth(10); // Kill it

        $aquarium->addFish($deadFish);

        $reproductionService = $this->createReproductionService();
        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        $action = new FishReproduceAction($deadFish, $aquarium, $reproductionService, $randomGenerator);
        $action->execute();

        $this->assertCount(1, $aquarium->getFishes());
    }

    public function test_execute_does_nothing_when_fish_is_hungry(): void
    {
        $aquarium = $this->createAquarium();
        $hungryFish = $this->createFish(Sex::MALE, 3);
        $hungryFish->loseHealth(6); // HP = 4, hungry

        $aquarium->addFish($hungryFish);

        $reproductionService = $this->createReproductionService();
        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        $action = new FishReproduceAction($hungryFish, $aquarium, $reproductionService, $randomGenerator);
        $action->execute();

        $this->assertCount(1, $aquarium->getFishes());
    }

    public function test_execute_does_nothing_when_fish_too_young(): void
    {
        $aquarium = $this->createAquarium();
        $youngFish = $this->createFish(Sex::MALE, 1); // Age < 2

        $aquarium->addFish($youngFish);

        $reproductionService = $this->createReproductionService();
        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        $action = new FishReproduceAction($youngFish, $aquarium, $reproductionService, $randomGenerator);
        $action->execute();

        $this->assertCount(1, $aquarium->getFishes());
    }

    public function test_execute_creates_offspring_when_valid_partner_exists(): void
    {
        $aquarium = $this->createAquarium();
        $maleCarp = $this->createFish(Sex::MALE, 3);
        $femaleCarp = $this->createFish(Sex::FEMALE, 3);

        $aquarium->addFish($maleCarp);
        $aquarium->addFish($femaleCarp);

        $reproductionService = $this->createReproductionService();
        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('selectRandom')->willReturn($femaleCarp);
        $randomGenerator->method('randomBoolean')->willReturn(true);

        $action = new FishReproduceAction($maleCarp, $aquarium, $reproductionService, $randomGenerator);
        $action->execute();

        $this->assertCount(3, $aquarium->getFishes()); // Parents + offspring
    }

    private function createReproductionService(): ReproductionService
    {
        $strategies = [new MonosexualReproductionStrategy()];
        return new ReproductionService($strategies);
    }

    private function createFish(Sex $sex, int $age): Fish
    {
        return new Fish(
            id: FishId::generate(),
            name: new EntityName('Test Fish'),
            species: Species::CARP,
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
