<?php

declare(strict_types=1);

namespace Local\Token\Driver;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Firebase\FirebaseDriver;
use Local\Token\Exception\TokenExpirationException;
use Local\Token\Exception\TokenValidationException;
use Local\Token\TokenParserInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @template T of array{
 *     iat: \DateTimeImmutable,
 *     exp?: \DateTimeImmutable,
 *     typ: non-empty-string,
 *     alg: non-empty-string,
 *     ...
 * }
 * @template-implements TokenParserInterface<T>
 */
final readonly class FirebaseTokenParser extends FirebaseDriver implements TokenParserInterface
{
    /**
     * @param non-empty-string $publicKey
     */
    public function __construct(
        string $publicKey,
        AlgoInterface $algo = Algo::DEFAULT,
        ClockInterface $clock = new NativeClock(),
    ) {
        parent::__construct($publicKey, $algo, $clock);
    }

    public function parse(string $token): array
    {
        $now = $this->clock->now();

        try {
            $key = new Key($this->key, $this->algo->getName());

            $result = (array) JWT::decode($token, $key);

            if (isset($result['iat']) && \is_numeric($result['iat'])) {
                $result['iat'] = $now->setTimestamp((int) $result['iat']);
            }

            if (isset($result['exp']) && \is_numeric($result['exp'])) {
                $result['exp'] = $now->setTimestamp((int) $result['exp']);
            }

            $result['typ'] ??= 'JWT';
            $result['alg'] ??= $this->algo->getName();

            /** @var T */
            return $result;
        } catch (ExpiredException $e) {
            throw TokenExpirationException::fromExpiredToken($e);
        } catch (\Throwable $e) {
            throw TokenValidationException::fromInvalidFormat($e->getMessage(), $e);
        }
    }
}
