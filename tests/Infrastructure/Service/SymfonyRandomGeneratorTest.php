<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Service;

use App\Infrastructure\Service\SymfonyRandomGenerator;
use PHPUnit\Framework\TestCase;

final class SymfonyRandomGeneratorTest extends TestCase
{
    private SymfonyRandomGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new SymfonyRandomGenerator();
    }

    public function test_select_random_returns_element_from_array(): void
    {
        // Given
        $items = ['apple', 'banana', 'cherry'];

        // When
        $result = $this->generator->selectRandom($items);

        // Then
        $this->assertContains($result, $items);
    }

    public function test_select_random_with_single_element(): void
    {
        // Given
        $items = ['only-one'];

        // When
        $result = $this->generator->selectRandom($items);

        // Then
        $this->assertSame('only-one', $result);
    }

    public function test_random_boolean_returns_true_or_false(): void
    {
        // Given
        $results = [];

        // When - collect multiple results to verify randomness
        for ($i = 0; $i < 100; ++$i) {
            $results[] = $this->generator->randomBoolean();
        }

        // Then - at least one true and one false (statistically very likely)
        $this->assertContains(true, $results);
        $this->assertContains(false, $results);
    }

    public function test_select_random_throws_on_empty_array(): void
    {
        // Then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot select from empty array');

        // When
        $this->generator->selectRandom([]);
    }

    public function test_shuffle_returns_same_elements(): void
    {
        // Given
        $items = ['a', 'b', 'c', 'd', 'e'];

        // When
        $shuffled = $this->generator->shuffle($items);

        // Then
        $this->assertCount(5, $shuffled);
        $this->assertEqualsCanonicalizing($items, $shuffled); // Same elements, possibly different order
    }

    public function test_shuffle_empty_array(): void
    {
        // Given
        $items = [];

        // When
        $shuffled = $this->generator->shuffle($items);

        // Then
        $this->assertSame([], $shuffled);
    }
}
