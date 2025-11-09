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
}
