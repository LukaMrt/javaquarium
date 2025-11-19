<?php

declare(strict_types=1);

namespace App\Tests\Domain\Action;

use App\Domain\Action\FishFeedAction;
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

final class FishFeedActionTest extends TestCase
{
    private FeedingService $feedingService;

    protected function setUp(): void
    {
        $this->feedingService = new FeedingService([
            new HerbivorousFeedingStrategy(),
            new CarnivorousFeedingStrategy(),
        ]);
    }

    public function test_it_makes_hungry_fish_eat_when_food_available(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $hungryFish = new Fish(
            FishId::generate(),
            new EntityName('Hungry Fish'),
            Species::SOLE, // Herbivore
            Sex::MALE,
            Age::initial(),
            new HealthPoints(4) // Hungry (< 5)
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addFish($hungryFish);
        $aquarium->addAlgae($algae);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('selectRandom')->willReturn($algae);

        $action = new FishFeedAction($hungryFish, $aquarium, $this->feedingService, $randomGenerator);

        // When
        $action->execute();

        // Then
        $expectedFishHP = 4 + GameRules::HERBIVOROUS_HP_GAIN;
        $expectedAlgaeHP = 5 - GameRules::ALGAE_HP_LOSS_WHEN_EATEN;

        $this->assertSame($expectedFishHP, $hungryFish->getHealthPoints()->toInt());
        $this->assertSame($expectedAlgaeHP, $algae->getHealthPoints()->toInt());
    }

    public function test_it_does_nothing_when_fish_is_not_hungry(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $wellFedFish = new Fish(
            FishId::generate(),
            new EntityName('Well Fed Fish'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(8) // Not hungry (>= 5)
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addFish($wellFedFish);
        $aquarium->addAlgae($algae);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->expects($this->never())->method('selectRandom');

        $action = new FishFeedAction($wellFedFish, $aquarium, $this->feedingService, $randomGenerator);

        // When
        $action->execute();

        // Then
        $this->assertSame(8, $wellFedFish->getHealthPoints()->toInt()); // No change
        $this->assertSame(5, $algae->getHealthPoints()->toInt()); // No change
    }

    public function test_it_does_nothing_when_fish_is_dead(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $deadFish = new Fish(
            FishId::generate(),
            new EntityName('Dead Fish'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(0) // Dead
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addFish($deadFish);
        $aquarium->addAlgae($algae);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->expects($this->never())->method('selectRandom');

        $action = new FishFeedAction($deadFish, $aquarium, $this->feedingService, $randomGenerator);

        // When
        $action->execute();

        // Then
        $this->assertSame(0, $deadFish->getHealthPoints()->toInt());
        $this->assertSame(5, $algae->getHealthPoints()->toInt()); // No change
    }

    public function test_it_does_not_feed_when_fish_dies_before_action(): void
    {
        // Given
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
            new HealthPoints(3) // Hungry
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addFish($fish);
        $aquarium->addAlgae($algae);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->expects($this->never())->method('selectRandom');

        $action = new FishFeedAction($fish, $aquarium, $this->feedingService, $randomGenerator);

        // Simulate fish being killed before its action
        $fish->loseHealth(3); // Dies

        // When
        $action->execute();

        // Then
        $this->assertSame(0, $fish->getHealthPoints()->toInt());
        $this->assertSame(5, $algae->getHealthPoints()->toInt()); // No change
    }
}
