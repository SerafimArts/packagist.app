<?php

declare(strict_types=1);

namespace Local\Token\Driver\Lcobucci;

use Lcobucci\JWT\Signer;
use Local\Token\Algo;
use Local\Token\AlgoInterface;

/**
 * @internal this is an internal library class, please do not use it in your code
 * @psalm-internal Local\Token\Driver\Lcobucci
 */
final class SignerFactory
{
    public function create(AlgoInterface $algo): Signer
    {
        if (!$algo instanceof Algo) {
            $algo = Algo::tryFrom($algo->getName()) ?? $algo;
        }

        return match ($algo) {
            Algo::ES256 => new Signer\Ecdsa\Sha256(),
            Algo::ES384 => new Signer\Ecdsa\Sha384(),
            Algo::ES512 => new Signer\Ecdsa\Sha512(),
            Algo::HS256 => new Signer\Hmac\Sha256(),
            Algo::HS384 => new Signer\Hmac\Sha384(),
            Algo::HS512 => new Signer\Hmac\Sha512(),
            Algo::RS256 => new Signer\Rsa\Sha256(),
            Algo::RS384 => new Signer\Rsa\Sha384(),
            Algo::RS512 => new Signer\Rsa\Sha512(),
            default => throw new \InvalidArgumentException(\sprintf(
                'Unsupported "%s" algorithm',
                $algo->getName(),
            )),
        };
    }
}
