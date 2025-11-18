<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Shared\ValueObject\EntityName;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class EntityNameType extends Type
{
    public const string TYPE_NAME = 'entity_name';

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    #[\Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof EntityName) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s, got %s',
                EntityName::class,
                get_debug_type($value)
            ));
        }

        return $value->toString();
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?EntityName
    {
        if ($value === null || $value instanceof EntityName) {
            return $value;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected string, got %s',
                get_debug_type($value)
            ));
        }

        return new EntityName($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
