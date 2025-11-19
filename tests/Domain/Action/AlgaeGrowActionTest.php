<?php

declare(strict_types=1);

namespace App\Tests\Domain\Action;

use App\Domain\Action\AlgaeGrowAction;
use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Aquarium\Entity\Aquarium;
use App\Domain\Aquarium\ValueObject\AquariumId;
use App\Domain\Aquarium\ValueObject\TurnNumber;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class AlgaeGrowActionTest extends TestCase
{
    public function test_it_makes_algae_grow_when_alive(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addAlgae($algae);

        $action = new AlgaeGrowAction($algae, $aquarium);

        // When
        $action->execute();

        // Then
        $this->assertSame(6, $algae->getHealthPoints()->toInt());
    }

    public function test_it_adds_offspring_to_aquarium_when_splitting(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(9) // Will reach 10 and split
        );

        $aquarium->addAlgae($algae);

        $action = new AlgaeGrowAction($algae, $aquarium);

        // When
        $action->execute();

        // Then
        $this->assertCount(2, $aquarium->getAlgae()); // Parent + offspring
        $this->assertSame(5, $algae->getHealthPoints()->toInt()); // Parent has 5 HP
    }

    public function test_it_does_nothing_when_algae_is_dead(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $deadAlgae = new Algae(
            AlgaeId::generate(),
            new EntityName('Dead Algae'),
            Age::initial(),
            new HealthPoints(0) // Dead
        );

        $aquarium->addAlgae($deadAlgae);

        $action = new AlgaeGrowAction($deadAlgae, $aquarium);

        // When
        $action->execute();

        // Then
        $this->assertSame(0, $deadAlgae->getHealthPoints()->toInt()); // Still dead
        $this->assertCount(1, $aquarium->getAlgae()); // No offspring
    }

    public function test_it_does_not_grow_when_algae_dies_before_action(): void
    {
        // Given
        $aquarium = new Aquarium(
            AquariumId::generate(),
            new EntityName('Test'),
            TurnNumber::initial()
        );

        $algae = new Algae(
            AlgaeId::generate(),
            new EntityName('Green Algae'),
            Age::initial(),
            new HealthPoints(5)
        );

        $aquarium->addAlgae($algae);

        $action = new AlgaeGrowAction($algae, $aquarium);

        // Simulate algae being eaten before its action
        $algae->loseHealth(5); // Dies

        // When
        $action->execute();

        // Then
        $this->assertSame(0, $algae->getHealthPoints()->toInt());
        $this->assertCount(1, $aquarium->getAlgae()); // No offspring
    }
}
