<?php

declare(strict_types=1);

namespace App\Tests\Domain\Shared\ValueObject;

use App\Domain\Shared\ValueObject\Age;
use PHPUnit\Framework\TestCase;

final class AgeTest extends TestCase
{
    public function test_it_creates_age(): void
    {
        // When
        $age = new Age(5);

        // Then
        $this->assertSame(5, $age->toInt());
    }

    public function test_it_increments_age(): void
    {
        // Given
        $age = new Age(5);

        // When
        $newAge = $age->increment();

        // Then
        $this->assertSame(6, $newAge->toInt());
    }

    public function test_equals(): void
    {
        // Given
        $age1 = new Age(5);
        $age2 = new Age(5);
        $age3 = new Age(6);

        // Then
        $this->assertTrue($age1->equals($age2));
        $this->assertFalse($age1->equals($age3));
    }
}
