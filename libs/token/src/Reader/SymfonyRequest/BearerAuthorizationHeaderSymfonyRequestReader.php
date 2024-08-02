<?php

declare(strict_types=1);

namespace Local\Token\Reader\SymfonyRequest;

use Local\Token\Reader\TokenReaderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Возвращает значение токена из запроса и убирает префикс "bearer"
 */
final class BearerAuthorizationHeaderSymfonyRequestReader extends SymfonyRequestTokenReader
{
    /**
     * @var non-empty-string
     */
    private const string TOKEN_PREFIX = 'bearer ';

    /**
     * @var TokenReaderInterface<mixed>
     */
    private TokenReaderInterface $delegate;

    /**
     * @param TokenReaderInterface<mixed> $delegate
     */
    public function __construct(TokenReaderInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    public function isReadable(mixed $container): bool
    {
        // @phpstan-ignore-next-line : Template param contradiction
        assert($container instanceof Request);

        return $this->delegate->isReadable($container)
            && self::isHeaderPrefixedBearer($this->delegate->read($container));
    }

    /**
     * Returns the header prefix like "Bearer " if the passed token contains
     * a "Bearer " prefix.
     *
     * @return lowercase-string
     */
    private static function getHeaderPrefix(string $token): string
    {
        $prefix = \substr($token, 0, \strlen(self::TOKEN_PREFIX));

        return \strtolower($prefix);
    }

    /**
     * Returns the header suffix without the "Bearer " header prefix.
     */
    private static function getHeaderSuffix(string $token): string
    {
        $suffix = \substr($token, \strlen(self::TOKEN_PREFIX));

        return \trim($suffix);
    }

    /**
     * Returns {@see true} in case of passed token string
     * contains a "Bearer " prefix or {@see false} instead.
     */
    private static function isHeaderPrefixedBearer(string $token): bool
    {
        return \strlen($token) >= \strlen(self::TOKEN_PREFIX)
            && self::getHeaderPrefix($token) === self::TOKEN_PREFIX;
    }

    public function read(mixed $container): string
    {
        // @phpstan-ignore-next-line : Template param contradiction
        assert($container instanceof Request);

        $result = $this->delegate->read($container);

        // В том случае если заголовок не содержит префикса `bearer`,
        // то возвращаем заголовок "как есть" (без лишних пробелов, если есть)
        // и пытаемся далее распарсить уже его оригинальный вариант.
        if (!self::isHeaderPrefixedBearer($result)) {
            return '';
        }

        return self::getHeaderSuffix($result);
    }
}
