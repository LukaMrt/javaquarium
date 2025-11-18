<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Aquarium\ValueObject\AquariumId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class AquariumIdType extends Type
{
    public const string TYPE_NAME = 'aquarium_id';

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

        if (!$value instanceof AquariumId) {
            throw new \InvalidArgumentException(sprintf(
                'Expected %s, got %s',
                AquariumId::class,
                get_debug_type($value)
            ));
        }

        return $value->toString();
    }

    #[\Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AquariumId
    {
        if ($value === null || $value instanceof AquariumId) {
            return $value;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected string, got %s',
                get_debug_type($value)
            ));
        }

        return AquariumId::fromString($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
