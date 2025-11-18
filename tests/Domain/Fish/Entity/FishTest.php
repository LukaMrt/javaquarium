<?php

declare(strict_types=1);

namespace App\Tests\Domain\Fish\Entity;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class FishTest extends TestCase
{
    public function test_it_creates_fish_with_initial_values(): void
    {
        // Given
        $id = FishId::generate();
        $name = new EntityName('Nemo');

        // When
        $fish = new Fish($id, $name, Species::CLOWNFISH, Sex::MALE, Age::initial(), HealthPoints::initial());

        // Then
        $this->assertTrue($fish->getId()->equals($id));
        $this->assertSame('Nemo', $fish->getName()->toString());
        $this->assertSame(Species::CLOWNFISH, $fish->getSpecies());
        $this->assertSame(Sex::MALE, $fish->getSex());
        $this->assertSame(0, $fish->getAge()->toInt());
        $this->assertSame(GameRules::INITIAL_HP, $fish->getHealthPoints()->toInt());
    }

    public function test_lose_health_decreases_hp(): void
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
        $fish->loseHealth(3);

        // Then
        $this->assertSame(7, $fish->getHealthPoints()->toInt());
    }

    public function test_gain_health_increases_hp(): void
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

        // When
        $fish->gainHealth(3);

        // Then
        $this->assertSame(8, $fish->getHealthPoints()->toInt());
    }

    public function test_age_increments_age(): void
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
        $fish->age();

        // Then
        $this->assertSame(1, $fish->getAge()->toInt());
    }

    public function test_is_hungry_returns_true_when_hungry(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(GameRules::HUNGER_THRESHOLD)
        );

        // When & Then
        $this->assertTrue($fish->isHungry());
    }

    public function test_is_hungry_returns_false_when_not_hungry(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(GameRules::HUNGER_THRESHOLD + 1)
        );

        // When & Then
        $this->assertFalse($fish->isHungry());
    }

    public function test_is_dead_returns_true_when_dead(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(0)
        );

        // When & Then
        $this->assertTrue($fish->isDead());
    }

    public function test_is_dead_returns_false_when_alive(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(1)
        );

        // When & Then
        $this->assertFalse($fish->isDead());
    }
}
