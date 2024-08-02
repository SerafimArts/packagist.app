<?php

declare(strict_types=1);

namespace Local\Token;

use Firebase\JWT\JWT;
use Lcobucci\JWT\Parser;
use Local\Token\Driver\FirebaseTokenBuilder;
use Local\Token\Driver\LcobucciTokenBuilder;
use Local\Token\Exception\InvalidKeyException;

/**
 * @template T of array<non-empty-string, mixed>
 * @template-implements TokenBuilderFactoryInterface<T>
 */
final readonly class TokenBuilderFactory extends TokenFactory implements TokenBuilderFactoryInterface
{
    public function create(
        #[\SensitiveParameter]
        string $privateKey,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenBuilderInterface {
        // @phpstan-ignore-next-line : Additional key size assertion
        if ($privateKey === '') {
            throw InvalidKeyException::fromEmptyKey();
        }

        if (\interface_exists(Parser::class)) {
            /** @var LcobucciTokenBuilder<T> */
            return new LcobucciTokenBuilder($privateKey, $algo, $this->clock, $passphrase);
        }

        if (\class_exists(JWT::class)) {
            if ($passphrase !== '') {
                throw new \InvalidArgumentException('firebase/jwt package does not support key passphrase');
            }

            /** @var FirebaseTokenBuilder<T> */
            return new FirebaseTokenBuilder($privateKey, $algo, $this->clock);
        }

        throw new \LogicException('No suitable JWT drivers found');
    }

    public function createFromFile(
        #[\SensitiveParameter]
        string $privateKeyPathname,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenBuilderInterface {
        $contents = $this->tryRead($privateKeyPathname);

        if ($contents === '') {
            throw InvalidKeyException::fromEmptyKeyPathname($privateKeyPathname);
        }

        return self::create($contents, $algo, $passphrase);
    }
}
