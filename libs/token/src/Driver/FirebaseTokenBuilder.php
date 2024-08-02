<?php

declare(strict_types=1);

namespace Local\Token\Driver;

use Firebase\JWT\JWT;
use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Firebase\FirebaseDriver;
use Local\Token\TokenBuilderInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @template T of array<non-empty-string, mixed>
 * @template-implements TokenBuilderInterface<T>
 */
final readonly class FirebaseTokenBuilder extends FirebaseDriver implements TokenBuilderInterface
{
    /**
     * @param non-empty-string $privateKey
     */
    public function __construct(
        string $privateKey,
        AlgoInterface $algo = Algo::DEFAULT,
        ClockInterface $clock = new NativeClock(),
    ) {
        parent::__construct($privateKey, $algo, $clock);
    }

    public function build(array $payload, mixed $expiresAt = null): string
    {
        $now = $this->clock->now();

        $payload['iat'] = $now->getTimestamp();

        if ($expiresAt !== null) {
            $expirationDateTime = $this->createExpiresAt($now, $expiresAt);

            $payload['exp'] = $expirationDateTime->getTimestamp();
        }

        /** @var non-empty-string */
        return JWT::encode($payload, $this->key, $this->algo->getName());
    }
}
