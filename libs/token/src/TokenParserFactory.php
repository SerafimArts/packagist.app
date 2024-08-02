<?php

declare(strict_types=1);

namespace Local\Token;

use Firebase\JWT\JWT;
use Lcobucci\JWT\Parser;
use Local\Token\Driver\FirebaseTokenParser;
use Local\Token\Driver\LcobucciTokenParser;
use Local\Token\Exception\InvalidKeyException;

/**
 * @template T of array{
 *     iat: \DateTimeImmutable,
 *     exp?: \DateTimeImmutable,
 *     typ: non-empty-string,
 *     alg: non-empty-string,
 *     ...
 * }
 * @template-implements TokenParserFactoryInterface<T>
 */
final readonly class TokenParserFactory extends TokenFactory implements TokenParserFactoryInterface
{
    public function create(
        #[\SensitiveParameter]
        string $publicKey,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenParserInterface {
        // @phpstan-ignore-next-line : Additional key size assertion
        if ($publicKey === '') {
            throw InvalidKeyException::fromEmptyKey();
        }

        if (\interface_exists(Parser::class)) {
            /** @var LcobucciTokenParser<T> */
            return new LcobucciTokenParser($publicKey, $algo, $this->clock, $passphrase);
        }

        if (\class_exists(JWT::class)) {
            if ($passphrase !== '') {
                throw new \InvalidArgumentException('firebase/jwt package does not support key passphrase');
            }

            /** @var FirebaseTokenParser<T> */
            return new FirebaseTokenParser($publicKey, $algo, $this->clock);
        }

        throw new \LogicException('No suitable JWT drivers found');
    }

    public function createFromFile(
        #[\SensitiveParameter]
        string $publicKeyPathname,
        AlgoInterface $algo = Algo::DEFAULT,
        #[\SensitiveParameter]
        string $passphrase = ''
    ): TokenParserInterface {
        $contents = $this->tryRead($publicKeyPathname);

        if ($contents === '') {
            throw InvalidKeyException::fromEmptyKeyPathname($publicKeyPathname);
        }

        return self::create($contents, $algo, $passphrase);
    }
}
