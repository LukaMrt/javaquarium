<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use App\Domain\Shared\GameRules;

final readonly class HealthPoints
{
    public function __construct(
        private int $value
    ) {
    }

    public static function initial(): self
    {
        return new self(GameRules::INITIAL_HP);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function add(int $amount): self
    {
        $newValue = min($this->value + $amount, GameRules::MAX_HP);
        return new self($newValue);
    }

    public function subtract(int $amount): self
    {
        $newValue = max($this->value - $amount, 0);
        return new self($newValue);
    }

    public function isHungry(): bool
    {
        return $this->value <= GameRules::HUNGER_THRESHOLD;
    }

    public function isDead(): bool
    {
        return $this->value === 0;
    }
}
