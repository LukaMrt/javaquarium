<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Shared\ValueObject\Age;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class AgeType extends Type
{
    public const string TYPE_NAME = 'age';

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

        if (!$value instanceof Age) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s, got %s',
                Age::class,
                get_debug_type($value)
            ));
        }

        return $value->toInt();
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Age
    {
        if ($value === null || $value instanceof Age) {
            return $value;
        }

        if (!is_int($value) && !is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected int, got %s',
                get_debug_type($value)
            ));
        }

        return new Age((int) $value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
