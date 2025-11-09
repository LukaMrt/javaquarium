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
}
