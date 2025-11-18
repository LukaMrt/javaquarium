<?php

declare(strict_types=1);

namespace App\Tests\Domain\Service;

use App\Domain\Fish\Entity\Fish;
use App\Domain\Fish\ValueObject\FishId;
use App\Domain\Fish\ValueObject\Sex;
use App\Domain\Fish\ValueObject\Species;
use App\Domain\Service\HungerService;
use App\Domain\Shared\GameRules;
use App\Domain\Shared\ValueObject\Age;
use App\Domain\Shared\ValueObject\EntityName;
use App\Domain\Shared\ValueObject\HealthPoints;
use PHPUnit\Framework\TestCase;

final class HungerServiceTest extends TestCase
{
    private HungerService $service;

    protected function setUp(): void
    {
        $this->service = new HungerService();
    }

    public function test_is_hungry_returns_true_when_fish_is_hungry(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(GameRules::HUNGER_THRESHOLD)
        );

        // When & Then
        $this->assertTrue($this->service->isHungry($fish));
    }

    public function test_is_hungry_returns_false_when_fish_is_not_hungry(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(GameRules::HUNGER_THRESHOLD + 1)
        );

        // When & Then
        $this->assertFalse($this->service->isHungry($fish));
    }

    public function test_apply_hunger_decreases_hp_by_1(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            HealthPoints::initial()
        );
        $initialHp = $fish->getHealthPoints()->toInt();

        // When
        $this->service->applyHunger($fish);

        // Then
        $this->assertSame($initialHp - GameRules::HP_LOSS_PER_TURN, $fish->getHealthPoints()->toInt());
    }

    public function test_apply_hunger_respects_min_hp(): void
    {
        // Given
        $fish = new Fish(
            FishId::generate(),
            new EntityName('Nemo'),
            Species::CLOWNFISH,
            Sex::MALE,
            Age::initial(),
            new HealthPoints(0)
        );

        // When
        $this->service->applyHunger($fish);

        // Then
        $this->assertSame(0, $fish->getHealthPoints()->toInt());
    }
}
