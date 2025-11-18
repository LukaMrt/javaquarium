<?php

declare(strict_types=1);

namespace App\Tests\Domain\Shared;

use App\Domain\Shared\GameRules;
use PHPUnit\Framework\TestCase;

final class GameRulesTest extends TestCase
{
    public function test_initial_hp_is_10(): void
    {
        // Then
        $this->assertSame(10, GameRules::INITIAL_HP);
    }

    public function test_max_hp_is_10(): void
    {
        // Then
        $this->assertSame(10, GameRules::MAX_HP);
    }

    public function test_hunger_threshold_is_5(): void
    {
        // Then
        $this->assertSame(5, GameRules::HUNGER_THRESHOLD);
    }

    public function test_hp_loss_per_turn_is_1(): void
    {
        // Then
        $this->assertSame(1, GameRules::HP_LOSS_PER_TURN);
    }

    public function test_herbivorous_hp_gain_is_3(): void
    {
        // Then
        $this->assertSame(3, GameRules::HERBIVOROUS_HP_GAIN);
    }

    public function test_carnivorous_hp_gain_is_5(): void
    {
        // Then
        $this->assertSame(5, GameRules::CARNIVOROUS_HP_GAIN);
    }

    public function test_fish_hp_loss_when_attacked_is_4(): void
    {
        // Then
        $this->assertSame(4, GameRules::FISH_HP_LOSS_WHEN_ATTACKED);
    }

    public function test_algae_hp_loss_when_eaten_is_2(): void
    {
        // Then
        $this->assertSame(2, GameRules::ALGAE_HP_LOSS_WHEN_EATEN);
    }
}
