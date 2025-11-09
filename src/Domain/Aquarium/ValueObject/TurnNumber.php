<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\ValueObject;

final readonly class TurnNumber
{
    public function __construct(
        private int $value
    ) {
    }

    public static function initial(): self
    {
        return new self(0);
    }

    public function increment(): self
    {
        return new self($this->value + 1);
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
