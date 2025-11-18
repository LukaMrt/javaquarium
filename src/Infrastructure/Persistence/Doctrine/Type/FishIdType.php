<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Fish\ValueObject\FishId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class FishIdType extends Type
{
    public const string TYPE_NAME = 'fish_id';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    #[\Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof FishId) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s, got %s',
                FishId::class,
                get_debug_type($value)
            ));
        }

        return $value->toString();
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?FishId
    {
        if ($value === null || $value instanceof FishId) {
            return $value;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected string, got %s',
                get_debug_type($value)
            ));
        }

        return FishId::fromString($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
