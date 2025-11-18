<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Shared\ValueObject\HealthPoints;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class HealthPointsType extends Type
{
    public const string TYPE_NAME = 'health_points';

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

        if (!$value instanceof HealthPoints) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s, got %s',
                HealthPoints::class,
                get_debug_type($value)
            ));
        }

        return $value->toInt();
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?HealthPoints
    {
        if ($value === null || $value instanceof HealthPoints) {
            return $value;
        }

        if (!is_int($value) && !is_numeric($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected int, got %s',
                get_debug_type($value)
            ));
        }

        return new HealthPoints((int) $value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
