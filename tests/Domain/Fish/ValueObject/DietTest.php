<?php

declare(strict_types=1);

namespace App\Tests\Domain\Fish\ValueObject;

use App\Domain\Fish\ValueObject\Diet;
use PHPUnit\Framework\TestCase;

final class DietTest extends TestCase
{
    public function testCarnivorousCase(): void
    {
        // Given
        $diet = Diet::CARNIVOROUS;

        // When & Then
        $this->assertSame('CARNIVOROUS', $diet->name);
    }

    public function testHerbivorousCase(): void
    {
        // Given
        $diet = Diet::HERBIVOROUS;

        // When & Then
        $this->assertSame('HERBIVOROUS', $diet->name);
    }

    public function testIsCarnivorous(): void
    {
        // Given
        $carnivorous = Diet::CARNIVOROUS;
        $herbivorous = Diet::HERBIVOROUS;

        // When & Then
        $this->assertTrue($carnivorous->isCarnivorous());
        $this->assertFalse($herbivorous->isCarnivorous());
    }

    public function testIsHerbivorous(): void
    {
        // Given
        $carnivorous = Diet::CARNIVOROUS;
        $herbivorous = Diet::HERBIVOROUS;

        // When & Then
        $this->assertFalse($carnivorous->isHerbivorous());
        $this->assertTrue($herbivorous->isHerbivorous());
    }
}