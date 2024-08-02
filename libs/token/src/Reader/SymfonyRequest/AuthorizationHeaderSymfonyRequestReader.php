<?php

declare(strict_types=1);

namespace Local\Token\Reader\SymfonyRequest;

use Symfony\Component\HttpFoundation\Request;

/**
 * Возвращает значение токена из заголовка Authorization запроса.
 */
final class AuthorizationHeaderSymfonyRequestReader extends SymfonyRequestTokenReader
{
    /**
     * @var non-empty-string
     */
    private const string TOKEN_HEADER = 'authorization';

    public function isReadable(mixed $container): bool
    {
        // @phpstan-ignore-next-line : Template param contradiction
        assert($container instanceof Request);

        return $container->headers->has(self::TOKEN_HEADER);
    }

    public function read(mixed $container): string
    {
        // @phpstan-ignore-next-line : Template param contradiction
        assert($container instanceof Request);

        $token = (string) $container->headers->get(self::TOKEN_HEADER, '');

        // Возвращаем заголовок "как есть": Без лишних пробелов, если есть.
        return \trim($token);
    }
}
