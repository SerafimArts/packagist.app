<?php

declare(strict_types=1);

namespace Local\Token;

enum Algo: string implements AlgoInterface
{
    /**
     * ECDSA 256-bit signature.
     */
    case ES256 = 'ES256';

    /**
     * ECDSA 384-bit signature.
     */
    case ES384 = 'ES384';

    /**
     * ECDSA 512-bit signature.
     */
    case ES512 = 'ES512';

    /**
     * HMAC 256-bit signature.
     */
    case HS256 = 'HS256';

    /**
     * HMAC 384-bit signature.
     */
    case HS384 = 'HS384';

    /**
     * HMAC 512-bit signature.
     */
    case HS512 = 'HS512';

    /**
     * RSA 256-bit signature.
     */
    case RS256 = 'RS256';

    /**
     * RSA 384-bit signature.
     */
    case RS384 = 'RS384';

    /**
     * RSA 512-bit signature.
     */
    case RS512 = 'RS512';

    /**
     * Default signature algorithm.
     */
    public const Algo DEFAULT = self::RS256;

    public function getName(): string
    {
        return $this->value;
    }
}
