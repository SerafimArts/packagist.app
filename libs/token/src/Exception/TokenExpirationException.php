<?php

declare(strict_types=1);

namespace Local\Token\Exception;

class TokenExpirationException extends TokenValidationException
{
    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_EXPIRATION = 0x01 + parent::CODE_LAST;

    /**
     * @var int
     */
    protected const CODE_LAST = self::ERROR_CODE_EXPIRATION;

    public static function fromExpiredToken(?\Throwable $prev = null): self
    {
        return new static('Token is expired', self::ERROR_CODE_EXPIRATION, $prev);
    }
}
