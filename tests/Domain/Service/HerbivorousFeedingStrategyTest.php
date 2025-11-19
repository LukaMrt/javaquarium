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
use App\Domain\Service\HerbivorousFeedingStrategy;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class HerbivorousFeedingStrategyTest extends TestCase
{
    private HerbivorousFeedingStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new HerbivorousFeedingStrategy();
    }

    public function test_it_supports_herbivorous_diet(): void
    {
        $this->assertTrue($this->strategy->supports(Diet::HERBIVOROUS));
    }

    public function test_it_does_not_support_carnivorous_diet(): void
    {
        $this->assertFalse($this->strategy->supports(Diet::CARNIVOROUS));
    }

    public function test_it_finds_living_algae_as_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Herbivore'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $algae1 = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae 1'),
            Age::initial(),
            HealthPoints::initial()
        );

        $algae2 = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae 2'),
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addAlgae($algae1);
        $aquarium->addAlgae($algae2);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertCount(2, $targets);
        $this->assertContains($algae1, $targets);
        $this->assertContains($algae2, $targets);
    }

    public function test_it_excludes_dead_algae_from_valid_targets(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Herbivore'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $livingAlgae = new Algae(
            AlgaeId::generate(),
            new EntityName('Living Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        $deadAlgae = new Algae(
            AlgaeId::generate(),
            new EntityName('Dead Algae'),
            Age::initial(),
            new HealthPoints(0) // Dead
        );

        $aquarium->addAlgae($livingAlgae);
        $aquarium->addAlgae($deadAlgae);

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertCount(1, $targets);
        $this->assertContains($livingAlgae, $targets);
        $this->assertNotContains($deadAlgae, $targets);
    }

    public function test_it_returns_empty_array_when_no_algae_available(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $predator = new Fish(
            FishId::generate(),
            new EntityName('Herbivore'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $targets = $this->strategy->findValidTargets($aquarium, $predator);

        // Then
        $this->assertEmpty($targets);
    }

    public function test_it_feeds_herbivorous_fish_with_algae(): void
    {
        // Given
        $predator = new Fish(
            FishId::generate(),
            new EntityName('Herbivore'),
            Species::SOLE,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(5)
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algae'),
            Age::initial(),
            new HealthPoints(3)
        );

        // When
        $this->strategy->feed($predator, $algae);

        // Then
        $expectedPredatorHP = 5 + GameRules::HERBIVOROUS_HP_GAIN;
        $expectedAlgaeHP = 3 - GameRules::ALGAE_HP_LOSS_WHEN_EATEN;

        $this->assertSame($expectedPredatorHP, $predator->getHealthPoints()->toInt());
        $this->assertSame($expectedAlgaeHP, $algae->getHealthPoints()->toInt());
    }
}
