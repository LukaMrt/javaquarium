<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Aquarium\ValueObject\TurnNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class TurnNumberType extends Type
{
    public const string TYPE_NAME = 'turn_number';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    #[\Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?int
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof TurnNumber) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s, got %s',
                TurnNumber::class,
                get_debug_type($value)
            ));
        }

        return $value->toInt();
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?TurnNumber
    {
        if ($value === null || $value instanceof TurnNumber) {
            return $value;
        }

        if (!is_int($value) && !is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected int, got %s',
                get_debug_type($value)
            ));
        }

        return new TurnNumber((int) $value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
