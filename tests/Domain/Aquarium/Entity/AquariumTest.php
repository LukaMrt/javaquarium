<?php

declare(strict_types=1);

namespace App\Tests\Domain\Aquarium\Entity;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\Fixtures\AquariumFixturesData;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Action\EntityActionProvider;
use App\Domain\Service\CarnivorousFeedingStrategy;
use App\Domain\Service\FeedingService;
use App\Domain\Service\HerbivorousFeedingStrategy;
use App\Domain\Service\RandomGeneratorInterface;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class AquariumTest extends TestCase
{
    private function createActionProvider(RandomGeneratorInterface $randomGenerator): EntityActionProvider
    {
        $feedingService = new FeedingService([
            new HerbivorousFeedingStrategy(),
            new CarnivorousFeedingStrategy(),
        ]);

        return new EntityActionProvider($feedingService, $randomGenerator);
    }

    public function test_it_creates_empty_aquarium(): void
    {
        // Given
        $id = AquariumId::generate();
        $name = new EntityName('Mon Aquarium');

        // When
        $aquarium = new Aquarium($id, $name, TurnNumber::initial());

        // Then
        $this->assertTrue($aquarium->getId()->equals($id));
        $this->assertSame('Mon Aquarium', $aquarium->getName()->toString());
        $this->assertSame(0, $aquarium->getTurnNumber()->toInt());
        $this->assertEmpty($aquarium->getFishes());
        $this->assertEmpty($aquarium->getAlgae());
    }

    public function test_it_adds_fish(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $aquarium->addFish($fish);

        // Then
        $this->assertCount(1, $aquarium->getFishes());
        $this->assertSame($fish, $aquarium->getFishes()[0]);
    }

    public function test_it_adds_algae(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Algue'),
            Age::initial(),
            HealthPoints::initial()
        );

        // When
        $aquarium->addAlgae($algae);

        // Then
        $this->assertCount(1, $aquarium->getAlgae());
        $this->assertSame($algae, $aquarium->getAlgae()[0]);
    }

    public function test_it_loads_from_fixtures(): void
    {
        // When
        $aquarium = AquariumFixturesData::get(AquariumFixturesData::TROPICAL_REEF);

        // Then
        $this->assertSame('Récif Tropical', $aquarium->getName()->toString());
        $this->assertSame(5, $aquarium->getTurnNumber()->toInt());
        $this->assertCount(3, $aquarium->getFishes());
        $this->assertCount(2, $aquarium->getAlgae());
    }

    public function test_advance_turn_increments_turn_number(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );
        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('shuffle')->willReturnArgument(0); // Return input unchanged

        // When
        $aquarium->advanceTurn($randomGenerator, $this->createActionProvider($randomGenerator));

        // Then
        $this->assertSame(1, $aquarium->getTurnNumber()->toInt());
    }

    public function test_advance_turn_ages_all_entities(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($fish);
        $aquarium->addAlgae($algae);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('shuffle')->willReturnArgument(0);

        // When
        $aquarium->advanceTurn($randomGenerator, $this->createActionProvider($randomGenerator));

        // Then
        $this->assertSame(1, $fish->getAge()->toInt());
        $this->assertSame(1, $algae->getAge()->toInt());
    }

    public function test_advance_turn_applies_hunger_to_all_fish(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($fish);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('shuffle')->willReturnArgument(0);

        // When
        $aquarium->advanceTurn($randomGenerator, $this->createActionProvider($randomGenerator));

        // Then
        $this->assertSame(GameRules::INITIAL_HP - GameRules::FISH_HP_LOSS_PER_TURN, $fish->getHealthPoints()->toInt());
    }

    public function test_advance_turn_hungry_fish_attempts_to_feed(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        // Hungry herbivorous fish
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CARP,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(GameRules::HUNGER_THRESHOLD)
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addFish($fish);
        $aquarium->addAlgae($algae);

        // Mock random to always select the algae
        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('shuffle')->willReturnArgument(0);
        $randomGenerator->method('selectRandom')
            ->willReturn($algae);

        // When
        $aquarium->advanceTurn($randomGenerator, $this->createActionProvider($randomGenerator));

        // Then - Fish should have eaten algae
        // After hunger: 5 - 1 = 4, after eating: 4 + 3 = 7
        $this->assertSame(GameRules::HUNGER_THRESHOLD - GameRules::FISH_HP_LOSS_PER_TURN + GameRules::HERBIVOROUS_HP_GAIN, $fish->getHealthPoints()->toInt());
        // Algae: 5 + 1 (growth) - 2 (eaten) = 4
        $this->assertSame(4, $algae->getHealthPoints()->toInt());
    }

    public function test_advance_turn_removes_dead_entities(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        // Fish that will die from hunger
        $dyingFish = new Fish(
            FishId::generate(),
            new EntityName('Dying'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(1) // Will die after losing 1 HP
        );

        // Healthy fish
        $healthyFish = new Fish(
            FishId::generate(),
            new EntityName('Healthy'),
            Species::BASS,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );

        $aquarium->addFish($dyingFish);
        $aquarium->addFish($healthyFish);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('shuffle')->willReturnArgument(0);

        // When
        $aquarium->advanceTurn($randomGenerator, $this->createActionProvider($randomGenerator));

        // Then - Only healthy fish remains
        $this->assertCount(1, $aquarium->getFishes());
        $this->assertSame($healthyFish, $aquarium->getFishes()[0]);
    }

    public function test_advance_turn_removes_fish_that_dies_of_old_age(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        // Old fish at MAX_AGE - 1 (will become MAX_AGE after aging)
        $oldFish = new Fish(
            FishId::generate(),
            new EntityName('Old Fish'),
            Species::CLOWNFISH,
            Sex::MALE,
            new Age(GameRules::MAX_AGE - 1),
            HealthPoints::initial()
        );

        // Young fish
        $youngFish = new Fish(
            FishId::generate(),
            new EntityName('Young Fish'),
            Species::BASS,
            Sex::MALE,
            new Age(5),
            HealthPoints::initial()
        );

        $aquarium->addFish($oldFish);
        $aquarium->addFish($youngFish);

        $randomGenerator = $this->createMock(RandomGeneratorInterface::class);
        $randomGenerator->method('shuffle')->willReturnArgument(0);

        // When
        $aquarium->advanceTurn($randomGenerator, $this->createActionProvider($randomGenerator));

        // Then - Old fish died and was removed, only young fish remains
        $this->assertCount(1, $aquarium->getFishes());
        $this->assertSame($youngFish, $aquarium->getFishes()[0]);
        $this->assertSame(6, $youngFish->getAge()->toInt()); // Aged by 1
    }
}
