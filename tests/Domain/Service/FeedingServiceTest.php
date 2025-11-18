<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Service\FeedingService;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class FeedingServiceTest extends TestCase
{
    private FeedingService $service;

    protected function setUp(): void
    {
        $this->service = new FeedingService();
    }

    public function test_cannot_feed_on_self(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->canFeed($fish, $fish);

        // Then
        $this->assertFalse($result);
    }

    public function test_cannot_feed_on_same_species(): void
    {
        // Given
        $fish1 = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $fish2 = new Fish(
            FishId::generate(),
            new EntityName('Dory'),
            Species::CLOWNFISH,
            Sex::FEMALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->canFeed($fish1, $fish2);

        // Then
        $this->assertFalse($result);
    }

    public function test_herbivorous_fish_can_eat_algae(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->canFeed($fish, $algae);

        // Then
        $this->assertTrue($result);
    }

    public function test_herbivorous_fish_cannot_eat_fish(): void
    {
        // Given
        $herbivore = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $carnivore = new Fish(
            FishId::generate(),
            new EntityName('Bruce'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->canFeed($herbivore, $carnivore);

        // Then
        $this->assertFalse($result);
    }

    public function test_carnivorous_fish_can_eat_fish(): void
    {
        // Given
        $predator = new Fish(
            FishId::generate(),
            new EntityName('Bruce'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $prey = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->canFeed($predator, $prey);

        // Then
        $this->assertTrue($result);
    }

    public function test_carnivorous_fish_cannot_eat_algae(): void
    {
        // Given
        $carnivore = new Fish(
            FishId::generate(),
            new EntityName('Bruce'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->canFeed($carnivore, $algae);

        // Then
        $this->assertFalse($result);
    }

    public function test_herbivore_feeding_on_algae_changes_hp_correctly(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->feed($fish, $algae);

        // Then
        $this->assertTrue($result->isSuccess());
        $this->assertSame(5 + GameRules::HERBIVOROUS_HP_GAIN, $fish->getHealthPoints()->toInt());
        $this->assertSame(GameRules::INITIAL_HP - GameRules::ALGAE_HP_LOSS_WHEN_EATEN, $algae->getHealthPoints()->toInt());
    }

    public function test_carnivore_feeding_on_fish_changes_hp_correctly(): void
    {
        // Given
        $predator = new Fish(
            FishId::generate(),
            new EntityName('Bruce'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );
        $prey = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->feed($predator, $prey);

        // Then
        $this->assertTrue($result->isSuccess());
        $this->assertSame(5 + GameRules::CARNIVOROUS_HP_GAIN, $predator->getHealthPoints()->toInt());
        $this->assertSame(GameRules::INITIAL_HP - GameRules::FISH_HP_LOSS_WHEN_ATTACKED, $prey->getHealthPoints()->toInt());
    }

    public function test_feeding_on_dead_target_fails(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $deadAlgae = new Algae(
            AlgaeId::generate(),
            new EntityName('Dead Algae'),
            Age::initial(),
            new HealthPoints(0)
        );

        // When
        $result = $this->service->feed($fish, $deadAlgae);

        // Then
        $this->assertFalse($result->isSuccess());
        $this->assertStringContainsString('dead', strtolower($result->getReason()));
    }

    public function test_invalid_feeding_attempt_fails(): void
    {
        // Given
        $herbivore = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $carnivore = new Fish(
            FishId::generate(),
            new EntityName('Bruce'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $result = $this->service->feed($herbivore, $carnivore);

        // Then
        $this->assertFalse($result->isSuccess());
    }
}
