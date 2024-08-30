<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @api
 *
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Shared\Infrastructure\Persistence\Type
 */
class StringArrayType extends Type
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return 'string[]';
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] ??= 255;

        return $platform->getStringTypeDeclarationSQL($column) . '[]';
    }

    /**
     * @param list<string|null>|null $value
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
            $result[] = match (true) {
                $item === null => 'NULL',
                $item === '' => '""',
                default => '"' . \addcslashes($item, '"') . '"',
            };
        }

        return '{' . \implode(',', $result) . '}';
    }

    /**
     * @param non-empty-string|null $value
     *
     * @return list<string|null>|null
     *
     * @phpstan-ignore-next-line Method covariance failure
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?array
    {
        if ($value === null) {
            return null;
        }

        if ($value === '{}') {
            return [];
        }

        $result = [];
        $chunks = \explode(',', \trim($value, '{}'));

        foreach ($chunks as $item) {
            $item = \stripcslashes($item);

            if ($item === 'NULL') {
                $item = null;
            }

            $result[] = $item;
        }

        return $result;
    }
}
