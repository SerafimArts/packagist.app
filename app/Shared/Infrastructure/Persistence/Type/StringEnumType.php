<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;

/**
 * @api
 */
abstract class StringEnumType extends Type
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return static::getEnumClass();
    }

    /**
     * @return class-string<\BackedEnum>
     */
    abstract protected static function getEnumClass(): string;

    /**
     * @psalm-suppress UnnecessaryVarAnnotation
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $class = static::getEnumClass();

        return \strtolower(\str_replace('\\', '_', $class));
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        /** @var non-empty-string */
        return match (true) {
            $value === null => null,
            \is_string($value) => $value,
            $value instanceof \BackedEnum => $value->value,
            default => throw InvalidType::new(
                $value,
                \get_debug_type($value),
                ['null', 'string', static::getEnumClass()],
            ),
        };
    }

    /**
     * @param non-empty-string|null $value
     *
     * @phpstan-ignore-next-line phpstan covariant suppression
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\BackedEnum
    {
        if ($value === null) {
            return null;
        }

        $class = static::getEnumClass();

        return $class::tryFrom($value);
    }
}
