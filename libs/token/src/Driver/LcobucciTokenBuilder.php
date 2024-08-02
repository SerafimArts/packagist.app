<?php

declare(strict_types=1);

namespace Local\Token\Driver;

use Lcobucci\JWT\Builder as BuilderInterface;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\RegisteredClaims;
use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Lcobucci\LcobucciDriver;
use Local\Token\TokenBuilderInterface;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;

/**
 * @template T of array<non-empty-string, mixed>
 * @template-implements TokenBuilderInterface<T>
 */
final readonly class LcobucciTokenBuilder extends LcobucciDriver implements TokenBuilderInterface
{
    private BuilderInterface $builder;

    /**
     * @param non-empty-string $publicKey
     */
    public function __construct(
        #[\SensitiveParameter]
        string $publicKey,
        AlgoInterface $algo = Algo::DEFAULT,
        ClockInterface $clock = new NativeClock(),
        #[\SensitiveParameter]
        string $passphrase = ''
    ) {
        parent::__construct($publicKey, $algo, $clock, $passphrase);

        $this->builder = new Builder(new JoseEncoder(), ChainedFormatter::default());
    }

    public function build(array $payload, mixed $expiresAt = null): string
    {
        $now = $this->clock->now();

        $builder = $this->builder->issuedAt($now);

        if ($expiresAt !== null) {
            $expirationDateTime = $this->createExpiresAt($now, $expiresAt);

            $builder = $builder->expiresAt($expirationDateTime);
        }

        foreach ($payload as $key => $value) {
            switch ($key) {
                case RegisteredClaims::ID:
                    $value = self::assertClaimIsScalar(RegisteredClaims::ID, $value);
                    $value = self::assertClaimIsNonEmptyString(RegisteredClaims::ID, (string) $value);

                    $builder = $builder->identifiedBy($value);
                    break;

                case RegisteredClaims::SUBJECT:
                    $value = self::assertClaimIsScalar(RegisteredClaims::SUBJECT, $value);
                    $value = self::assertClaimIsNonEmptyString(RegisteredClaims::SUBJECT, (string) $value);

                    $builder = $builder->relatedTo($value);
                    break;

                case RegisteredClaims::AUDIENCE:
                    $value = self::assertClaimIsScalar(RegisteredClaims::AUDIENCE, $value);
                    $value = self::assertClaimIsNonEmptyString(RegisteredClaims::AUDIENCE, (string) $value);

                    $builder = $builder->permittedFor($value);
                    break;

                default:
                    $builder = $builder->withClaim($key, $value);
            }
        }

        $token = $builder->getToken($this->signer, $this->key);

        /** @var non-empty-string */
        return $token->toString();
    }

    /**
     * @template TClaim of mixed
     *
     * @param TClaim $value
     *
     * @return (TClaim is scalar ? TClaim : never)
     */
    private static function assertClaimIsScalar(string $claim, mixed $value)/* : mixed */
    {
        if (\is_scalar($value)) {
            return $value;
        }

        $message = 'The "%s" claim must be a scalar, but %s given';
        $message = \sprintf($message, $claim, \get_debug_type($value));

        throw new \InvalidArgumentException($message);
    }

    /**
     * @template TClaim of string
     *
     * @param TClaim $value
     *
     * @return (TClaim is non-empty-string ? non-empty-string : never)
     */
    private static function assertClaimIsNonEmptyString(string $claim, string $value): string
    {
        if ($value !== '') {
            return $value;
        }

        $message = 'The "%s" claim must be a non empty string';
        $message = \sprintf($message, $claim);

        throw new \InvalidArgumentException($message);
    }
}
