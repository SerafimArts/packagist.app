<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use App\Shared\Domain\Id\ObjectId;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @template T of ObjectId
 */
abstract class ObjectIdType extends Type
{
    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return static::getClass();
    }

    /**
     * @return class-string<T>
     */
    abstract protected static function getClass(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'VARCHAR(24)';
    }

    /**
     * @param T|null $value
     *
     * @return non-empty-string|null
     *
     * @phpstan-ignore-next-line
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof ObjectId) {
            return $value->toString();
        }

        return null;
    }

    /**
     * @param non-empty-string|null $value
     *
     * @return T|null
     *
     * @phpstan-ignore-next-line
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ObjectId
    {
        if ($value === null) {
            return null;
        }

        /** @var class-string<T> $class */
        $class = static::getClass();

        return new $class($value);
    }

    public function getBindingType(): ParameterType
    {
        return ParameterType::LARGE_OBJECT;
    }
}
