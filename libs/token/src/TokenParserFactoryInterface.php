<?php

declare(strict_types=1);

namespace Local\Token;

/**
 * @template T of array{
 *     iat: \DateTimeImmutable,
 *     exp?: \DateTimeImmutable,
 *     typ: non-empty-string,
 *     alg: non-empty-string,
 *     ...
 * }
 */
interface TokenParserFactoryInterface
{
    /**
     * @param non-empty-string $publicKey
     *
     * @return TokenParserInterface<T>
     */
    public function create(
        #[\SensitiveParameter]
        string $publicKey,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenParserInterface;

    /**
     * @param non-empty-string $publicKeyPathname
     *
     * @return TokenParserInterface<T>
     */
    public function createFromFile(
        #[\SensitiveParameter]
        string $publicKeyPathname,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenParserInterface;
}
