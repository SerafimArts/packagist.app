<?php

declare(strict_types=1);

namespace Local\Token;

/**
 * @template T of array<non-empty-string, mixed>
 */
interface TokenBuilderFactoryInterface
{
    /**
     * @param non-empty-string $privateKey
     *
     * @return TokenBuilderInterface<T>
     */
    public function create(
        #[\SensitiveParameter]
        string $privateKey,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenBuilderInterface;

    /**
     * @param non-empty-string $privateKeyPathname
     *
     * @return TokenBuilderInterface<T>
     */
    public function createFromFile(
        #[\SensitiveParameter]
        string $privateKeyPathname,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenBuilderInterface;
}
