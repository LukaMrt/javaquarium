<?php

declare(strict_types=1);

namespace App\Domain\Aquarium\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
final readonly class TurnNumber
{
    public function __construct(
        #[Assert\PositiveOrZero(message: 'Turn number must be positive or zero, got {{ value }}')]
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
