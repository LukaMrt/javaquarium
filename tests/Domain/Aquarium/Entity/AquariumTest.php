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
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class AquariumTest extends TestCase
{
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
}
