<?php

declare(strict_types=1);

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class HealthPointsTest extends TestCase
{
    public function test_it_creates_health_points(): void
    {
        // When
        $hp = new HealthPoints(5);

        // Then
        $this->assertSame(5, $hp->toInt());
    }

    public function test_it_creates_initial_health_points(): void
    {
        // When
        $hp = HealthPoints::initial();

        // Then
        $this->assertSame(GameRules::INITIAL_HP, $hp->toInt());
    }

    public function test_equals(): void
    {
        // Given
        $hp1 = new HealthPoints(5);
        $hp2 = new HealthPoints(5);
        $hp3 = new HealthPoints(6);

        // Then
        $this->assertTrue($hp1->equals($hp2));
        $this->assertFalse($hp1->equals($hp3));
    }

    public function test_add_increases_health_points(): void
    {
        // Given
        $hp = new HealthPoints(5);

        // When
        $newHp = $hp->add(3);

        // Then
        $this->assertSame(8, $newHp->toInt());
        $this->assertSame(5, $hp->toInt()); // Original unchanged
    }

    public function test_add_respects_max_hp(): void
    {
        // Given
        $hp = new HealthPoints(8);

        // When
        $newHp = $hp->add(5);

        // Then
        $this->assertSame(GameRules::MAX_HP, $newHp->toInt());
    }

    public function test_subtract_decreases_health_points(): void
    {
        // Given
        $hp = new HealthPoints(5);

        // When
        $newHp = $hp->subtract(2);

        // Then
        $this->assertSame(3, $newHp->toInt());
        $this->assertSame(5, $hp->toInt()); // Original unchanged
    }

    public function test_subtract_respects_min_hp(): void
    {
        // Given
        $hp = new HealthPoints(3);

        // When
        $newHp = $hp->subtract(5);

        // Then
        $this->assertSame(0, $newHp->toInt());
    }

    public function test_is_hungry_when_hp_at_or_below_threshold(): void
    {
        // Given
        $hungry1 = new HealthPoints(GameRules::HUNGER_THRESHOLD);
        $hungry2 = new HealthPoints(GameRules::HUNGER_THRESHOLD - 1);
        $notHungry = new HealthPoints(GameRules::HUNGER_THRESHOLD + 1);

        // Then
        $this->assertTrue($hungry1->isHungry());
        $this->assertTrue($hungry2->isHungry());
        $this->assertFalse($notHungry->isHungry());
    }

    public function test_is_dead_when_hp_is_zero(): void
    {
        // Given
        $dead = new HealthPoints(0);
        $alive = new HealthPoints(1);

        // Then
        $this->assertTrue($dead->isDead());
        $this->assertFalse($alive->isDead());
    }
}
