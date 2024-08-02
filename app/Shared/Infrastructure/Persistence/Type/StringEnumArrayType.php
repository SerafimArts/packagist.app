<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class StringEnumArrayType extends StringArrayType
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return static::getEnumClass() . '[]';
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

        return \strtolower(\str_replace('\\', '_', $class)) . '[]';
    }

    /**
     * @param list<\BackedEnum|null>|null $value
     *
     * @return non-empty-string|null
     *
     * @phpstan-ignore-next-line Method covariance failure
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        $result = [];

        foreach ($value as $item) {
            $result[] = $item instanceof \BackedEnum ? $item->value : null;
        }

        // @phpstan-ignore-next-line
        return parent::convertToDatabaseValue($result, $platform);
    }

    /**
     * @param non-empty-string|null $value
     *
     * @return list<\BackedEnum|null>|null
     *
     * @phpstan-ignore-next-line Method covariance failure
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?array
    {
        $values = parent::convertToPHPValue($value, $platform);

        if ($values === null) {
            return null;
        }

        $class = static::getEnumClass();

        $result = [];

        foreach ($values as $item) {
            $result[] = $item === null ? null : $class::tryFrom($item);
        }

        return $result;
    }
}
