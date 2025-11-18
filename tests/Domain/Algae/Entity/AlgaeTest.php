<?php

declare(strict_types=1);

namespace App\Tests\Domain\Algae\Entity;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class AlgaeTest extends TestCase
{
    public function test_it_creates_algae_with_initial_values(): void
    {
        // Given
        $id = AlgaeId::generate();
        $name = new EntityName('Algue verte');

        // When
        $algae = new Algae($id, $name, Age::initial(), HealthPoints::initial());

        // Then
        $this->assertTrue($algae->getId()->equals($id));
        $this->assertSame('Algue verte', $algae->getName()->toString());
        $this->assertSame(0, $algae->getAge()->toInt());
        $this->assertSame(GameRules::INITIAL_HP, $algae->getHealthPoints()->toInt());
    }

    public function test_lose_health_decreases_hp(): void
    {
        // Given
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $algae->loseHealth(2);

        // Then
        $this->assertSame(8, $algae->getHealthPoints()->toInt());
    }

    public function test_gain_health_increases_hp(): void
    {
        // Given
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        // When
        $algae->gainHealth(3);

        // Then
        $this->assertSame(8, $algae->getHealthPoints()->toInt());
    }

    public function test_age_increments_age(): void
    {
        // Given
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $algae->age();

        // Then
        $this->assertSame(1, $algae->getAge()->toInt());
    }

    public function test_is_dead_returns_true_when_dead(): void
    {
        // Given
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(0)
        );

        // When & Then
        $this->assertTrue($algae->isDead());
    }

    public function test_is_dead_returns_false_when_alive(): void
    {
        // Given
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(1)
        );

        // When & Then
        $this->assertFalse($algae->isDead());
    }
}
