<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\Diet;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Service\CarnivorousFeedingStrategy;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class CarnivorousFeedingStrategyTest extends TestCase
{
    private CarnivorousFeedingStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new CarnivorousFeedingStrategy();
    }

    public function test_it_supports_carnivorous_diet(): void
    {
        $this->assertTrue($this->strategy->supports(Diet::CARNIVOROUS));
    }

    public function test_it_does_not_support_herbivorous_diet(): void
    {
        $this->assertFalse($this->strategy->supports(Diet::HERBIVOROUS));
    }

    public function test_it_finds_living_fish_from_different_species_as_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $prey1 = new Fish(
            FishId::generate(),
            new EntityName('Prey 1'),
            Species::CLOWNFISH,
            Sex::FEMALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $prey2 = new Fish(
            FishId::generate(),
            new EntityName('Prey 2'),
            Species::TUNA,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($predator);
        $aquarium->addFish($prey1);
        $aquarium->addFish($prey2);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertCount(2, $targets);
        $this->assertContains($prey1, $targets);
        $this->assertContains($prey2, $targets);
    }

    public function test_it_excludes_same_species_from_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $sameSpeciesFish = new Fish(
            FishId::generate(),
            new EntityName('Same Species'),
            Species::GROUPER, // Same as predator
            Sex::FEMALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $differentSpeciesFish = new Fish(
            FishId::generate(),
            new EntityName('Different Species'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($predator);
        $aquarium->addFish($sameSpeciesFish);
        $aquarium->addFish($differentSpeciesFish);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertCount(1, $targets);
        $this->assertContains($differentSpeciesFish, $targets);
        $this->assertNotContains($sameSpeciesFish, $targets);
    }

    public function test_it_excludes_itself_from_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($predator);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertEmpty($targets);
    }

    public function test_it_excludes_dead_fish_from_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $livingPrey = new Fish(
            FishId::generate(),
            new EntityName('Living Prey'),
            Species::CLOWNFISH,
            Sex::FEMALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $deadPrey = new Fish(
            FishId::generate(),
            new EntityName('Dead Prey'),
            Species::TUNA,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(0) // Dead
        );

        $aquarium->addFish($predator);
        $aquarium->addFish($livingPrey);
        $aquarium->addFish($deadPrey);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertCount(1, $targets);
        $this->assertContains($livingPrey, $targets);
        $this->assertNotContains($deadPrey, $targets);
    }

    public function test_it_returns_empty_array_when_no_valid_prey_available(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($predator);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertEmpty($targets);
    }

    public function test_it_does_not_include_algae_as_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($predator);
        $aquarium->addAlgae($algae);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertEmpty($targets);
    }

    public function test_it_feeds_carnivorous_fish_with_another_fish(): void
    {
        // Given
        $predator = new Fish(
            FishId::generate(),
            new EntityName('Predator'),
            Species::GROUPER,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );

        $prey = new Fish(
            FishId::generate(),
            new EntityName('Prey'),
            Species::CLOWNFISH,
            Sex::FEMALE,
            Age::initial(),
            new HealthPoints(7)
        );

        // When
        $this->strategy->feed($predator, $prey);

        // Then
        $expectedPredatorHP = 5 + GameRules::CARNIVOROUS_HP_GAIN;
        $expectedPreyHP = 7 - GameRules::FISH_HP_LOSS_WHEN_ATTACKED;

        $this->assertSame($expectedPredatorHP, $predator->getHealthPoints()->toInt());
        $this->assertSame($expectedPreyHP, $prey->getHealthPoints()->toInt());
    }
}
