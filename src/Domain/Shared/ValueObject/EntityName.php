<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Cascade]
final readonly class EntityName implements \Stringable
{
    public function __construct(
        #[Assert\NotBlank(message: 'Entity name cannot be blank')]
        #[Assert\Length(
            min: 1,
            max: 100,
            minMessage: 'Entity name must be at least {{ limit }} character long',
            maxMessage: 'Entity name cannot be longer than {{ limit }} characters'
        )]
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
