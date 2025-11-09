<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class Age
{
    private const int INITIAL_AGE = 0;

    public function __construct(
        private int $value
    ) {
    }

    public static function initial(): self
    {
        return new self(self::INITIAL_AGE);
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
