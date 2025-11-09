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
}
