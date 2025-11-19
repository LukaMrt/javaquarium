<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Service\CarnivorousFeedingStrategy;
use App\Domain\Service\FeedingService;
use App\Domain\Service\HerbivorousFeedingStrategy;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class FeedingServiceTest extends TestCase
{
    private FeedingService $feedingService;

    protected function setUp(): void
    {
        // Inject both strategies
        $this->feedingService = new FeedingService([
            new HerbivorousFeedingStrategy(),
            new CarnivorousFeedingStrategy(),
        ]);
    }

    public function test_it_feeds_herbivorous_fish_with_algae(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $herbivore = new Fish(
            FishId::generate(),
            new EntityName('Herbivore'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            new HealthPoints(3)
        );

        $aquarium->addFish($herbivore);
        $aquarium->addAlgae($algae);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('selectRandom')->willReturn($algae);

        // When
        $this->feedingService->attemptFeeding($herbivore, $aquarium, $randomGenerator);

        // Then
        $expectedHerbivoreHP = 5 + GameRules::HERBIVOROUS_HP_GAIN;
        $expectedAlgaeHP = 3 - GameRules::ALGAE_HP_LOSS_WHEN_EATEN;

        $this->assertSame($expectedHerbivoreHP, $herbivore->getHealthPoints()->toInt());
        $this->assertSame($expectedAlgaeHP, $algae->getHealthPoints()->toInt());
    }

    public function test_it_feeds_carnivorous_fish_with_another_fish(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $carnivore = new Fish(
            FishId::generate(),
            new EntityName('Carnivore'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );

        $prey = new Fish(
            FishId::generate(),
            new EntityName('Prey'),
            Species::CLOWNFISH,
            Sex::FEMALE,
            Age::initial(),
            new HealthPoints(7)
        );

        $aquarium->addFish($carnivore);
        $aquarium->addFish($prey);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('selectRandom')->willReturn($prey);

        // When
        $this->feedingService->attemptFeeding($carnivore, $aquarium, $randomGenerator);

        // Then
        $expectedCarnivoreHP = 5 + GameRules::CARNIVOROUS_HP_GAIN;
        $expectedPreyHP = 7 - GameRules::FISH_HP_LOSS_WHEN_ATTACKED;

        $this->assertSame($expectedCarnivoreHP, $carnivore->getHealthPoints()->toInt());
        $this->assertSame($expectedPreyHP, $prey->getHealthPoints()->toInt());
    }

    public function test_it_does_not_feed_when_no_valid_targets_available(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $herbivore = new Fish(
            FishId::generate(),
            new EntityName('Herbivore'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addFish($herbivore);
        // No algae available

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->expects($this->never())->method('selectRandom');

        // When
        $this->feedingService->attemptFeeding($herbivore, $aquarium, $randomGenerator);

        // Then
        $this->assertSame(5, $herbivore->getHealthPoints()->toInt()); // No change
    }

    public function test_it_throws_exception_when_no_strategy_supports_diet(): void
    {
        // Given
        $feedingService = new FeedingService([]); // No strategies

        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $fish = new Fish(
            FishId::generate(),
            new EntityName('Fish'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        // Expect
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No feeding strategy found for diet');

        // When
        $feedingService->attemptFeeding($fish, $aquarium, $randomGenerator);
    }
}
