<?php

declare(strict_types=1);

namespace App\Tests\Domain\Action;

use App\Domain\Action\AlgaeGrowAction;
use App\Domain\Action\EntityActionProvider;
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
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class EntityActionProviderTest extends TestCase
{
    private EntityActionProvider $provider;

    protected function setUp(): void
    {
        $feedingService = new FeedingService([
            new HerbivorousFeedingStrategy(),
            new CarnivorousFeedingStrategy(),
        ]);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);

        $this->provider = new EntityActionProvider($feedingService, $randomGenerator);
    }

    public function test_it_creates_algae_grow_action_for_algae(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $actions = $this->provider->getActionsFor($algae, $aquarium);

        // Then
        $this->assertCount(1, $actions);
        $this->assertInstanceOf(AlgaeGrowAction::class, $actions[0]);
    }

    public function test_it_creates_fish_feed_action_for_fish(): void
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
            HealthPoints::initial()
        );

        // When
        $actions = $this->provider->getActionsFor($fish, $aquarium);

        // Then
        $this->assertCount(1, $actions);
        $this->assertInstanceOf(FishFeedAction::class, $actions[0]);
    }

    public function test_it_returns_empty_array_for_unknown_entity_type(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $unknownEntity = new \stdClass();

        // When
        $actions = $this->provider->getActionsFor($unknownEntity, $aquarium);

        // Then
        $this->assertEmpty($actions);
    }
}
