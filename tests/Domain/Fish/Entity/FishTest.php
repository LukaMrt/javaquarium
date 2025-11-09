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
}
