<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\UriInterface;

final class UriType extends StringType
{
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof \Stringable) {
            $value = (string) $value;
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UriInterface
    {
        $result = parent::convertToPHPValue($value, $platform);

        if ($result !== null) {
            return new Uri($result);
        }

        return null;
    }
}
