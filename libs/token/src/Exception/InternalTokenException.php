<?php

declare(strict_types=1);

namespace Local\Token\Exception;

class InternalTokenException extends TokenException
{
    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_INTERNAL_ERROR = 0x01 + parent::CODE_LAST;

    /**
     * @var int
     */
    protected const CODE_LAST = self::ERROR_CODE_INTERNAL_ERROR;

    public static function fromInternalError(string $message, ?\Throwable $prev = null): self
    {
        return new static($message, self::ERROR_CODE_INTERNAL_ERROR, $prev);
    }
}
