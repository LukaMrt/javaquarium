<?php

declare(strict_types=1);

namespace App\Tests\Domain\Algae\Entity;

use App\Domain\Algae\Entity\Algae;
use App\Domain\Algae\ValueObject\AlgaeId;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class AlgaeTest extends TestCase
{
    public function test_it_creates_algae_with_initial_values(): void
    {
        // Given
        $id = AlgaeId::generate();
        $name = new EntityName('Algue verte');

        // When
        $algae = new Algae($id, $name, Age::initial(), HealthPoints::initial());

        // Then
        $this->assertTrue($algae->getId()->equals($id));
        $this->assertSame('Algue verte', $algae->getName()->toString());
        $this->assertSame(0, $algae->getAge()->toInt());
        $this->assertSame(GameRules::INITIAL_HP, $algae->getHealthPoints()->toInt());
    }
}
