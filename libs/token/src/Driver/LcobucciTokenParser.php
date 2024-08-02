<?php

declare(strict_types=1);

namespace Local\Token\Driver;

use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Parser as JWTParserInterface;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Validator;
use Local\Token\Algo;
use Local\Token\AlgoInterface;
use Local\Token\Driver\Lcobucci\LcobucciDriver;
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
final readonly class LcobucciTokenParser extends LcobucciDriver implements TokenParserInterface
{
    /**
     * @readonly
     */
    private JWTParserInterface $parser;

    /**
     * @readonly
     */
    private Validator $validator;

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

        $this->parser = new Parser(new JoseEncoder());
        $this->validator = new Validator();
    }

    public function parse(string $token): array
    {
        if ($token === '') {
            throw TokenValidationException::fromInvalidFormat('Token value cannot be empty');
        }

        try {
            $token = $this->parser->parse($token);
        } catch (\Throwable $e) {
            throw TokenValidationException::fromInvalidFormat($e->getMessage(), $e);
        }

        foreach ($this->getConstraints() as $name => $constraint) {
            if (!$this->validator->validate($token, $constraint)) {
                throw TokenValidationException::fromSegment($name);
            }
        }

        if ($token->isExpired($this->clock->now())) {
            throw TokenExpirationException::fromExpiredToken();
        }

        assert($token instanceof Plain);

        $claims = $token->claims();
        $headers = $token->headers();

        /** @var T */
        return \array_merge($headers->all(), $claims->all());
    }

    /**
     * @return iterable<non-empty-string, Constraint>
     */
    private function getConstraints(): iterable
    {
        yield 'signature' => new SignedWith($this->signer, $this->key);
    }
}
