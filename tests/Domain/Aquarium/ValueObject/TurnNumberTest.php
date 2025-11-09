<?php

declare(strict_types=1);

namespace App\Tests\Domain\Aquarium\ValueObject;

use App\Domain\Aquarium\ValueObject\TurnNumber;
use PHPUnit\Framework\TestCase;

final class TurnNumberTest extends TestCase
{
    public function test_it_creates_initial_turn(): void
    {
        // When
        $turn = TurnNumber::initial();

        // Then
        $this->assertSame(0, $turn->toInt());
    }

    public function test_it_creates_turn_number(): void
    {
        // When
        $turn = new TurnNumber(5);

        // Then
        $this->assertSame(5, $turn->toInt());
    }

    public function test_it_increments_turn(): void
    {
        // Given
        $turn = new TurnNumber(5);

        // When
        $newTurn = $turn->increment();

        // Then
        $this->assertSame(6, $newTurn->toInt());
    }

    public function test_equals(): void
    {
        // Given
        $turn1 = new TurnNumber(5);
        $turn2 = new TurnNumber(5);
        $turn3 = new TurnNumber(6);

        // Then
        $this->assertTrue($turn1->equals($turn2));
        $this->assertFalse($turn1->equals($turn3));
    }
}
