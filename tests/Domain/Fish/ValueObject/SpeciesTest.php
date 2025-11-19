<?php

declare(strict_types=1);

namespace App\Tests\Domain\Fish\ValueObject;

use App\Domain\Fish\ValueObject\Diet;
use App\Domain\Fish\ValueObject\Species;
use PHPUnit\Framework\TestCase;

final class SpeciesTest extends TestCase
{
    public function test_it_has_all_species(): void
    {
        // Then
        $this->assertEquals('GROUPER', Species::GROUPER->value);
        $this->assertEquals('TUNA', Species::TUNA->value);
        $this->assertEquals('CLOWNFISH', Species::CLOWNFISH->value);
        $this->assertEquals('SOLE', Species::SOLE->value);
        $this->assertEquals('BASS', Species::BASS->value);
        $this->assertEquals('CARP', Species::CARP->value);
    }

    public function test_it_returns_display_names(): void
    {
        // Then
        $this->assertSame('Mérou', Species::GROUPER->getDisplayName());
        $this->assertSame('Thon', Species::TUNA->getDisplayName());
        $this->assertSame('Poisson-clown', Species::CLOWNFISH->getDisplayName());
        $this->assertSame('Sole', Species::SOLE->getDisplayName());
        $this->assertSame('Bar', Species::BASS->getDisplayName());
        $this->assertSame('Carpe', Species::CARP->getDisplayName());
    }

    public function test_carnivorous_species_return_carnivorous_diet(): void
    {
        // Given
        $carnivorousSpecies = [
            Species::GROUPER,
            Species::TUNA,
            Species::CLOWNFISH,
        ];

        // Then
        foreach ($carnivorousSpecies as $species) {
            $this->assertSame(Diet::CARNIVOROUS, $species->getDiet());
        }
    }

    public function test_herbivorous_species_return_herbivorous_diet(): void
    {
        // Given
        $herbivorousSpecies = [
            Species::BASS,
            Species::SOLE,
            Species::CARP,
        ];

        // Then
        foreach ($herbivorousSpecies as $species) {
            $this->assertSame(Diet::HERBIVOROUS, $species->getDiet());
        }
    }
}
