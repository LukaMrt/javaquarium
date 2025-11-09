<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class EntityName implements \Stringable
{
    public function __construct(
        private string $value
    ) {
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
