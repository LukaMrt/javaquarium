<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Symfony\Component\Uid\Uuid;

abstract readonly class EntityId implements \Stringable
{
    protected function __construct(
        private Uuid $value
    ) {
    }

    public static function fromString(string $value): static
    {
        /** @phpstan-ignore new.static */
        return new static(Uuid::fromString($value));
    }

    public static function generate(): static
    {
        /** @phpstan-ignore new.static */
        return new static(Uuid::v7());
    }

    public function toString(): string
    {
        return $this->value->toRfc4122();
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
