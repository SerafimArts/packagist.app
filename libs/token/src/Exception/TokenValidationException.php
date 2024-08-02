<?php

declare(strict_types=1);

namespace Local\Token\Exception;

class TokenValidationException extends TokenException
{
    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_VALIDATION = 0x01 + parent::CODE_LAST;

    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_FORMAT = 0x02 + parent::CODE_LAST;

    /**
     * @var int
     */
    protected const CODE_LAST = self::ERROR_CODE_FORMAT;

    /**
     * @param non-empty-string $name
     */
    public static function fromSegment(string $name, ?\Throwable $prev = null): self
    {
        $message = \sprintf('Token "%s" validation failed', $name);

        return new static($message, self::ERROR_CODE_VALIDATION, $prev);
    }

    public static function fromInvalidFormat(string $message, ?\Throwable $prev = null): self
    {
        return new static($message, self::ERROR_CODE_FORMAT, $prev);
    }
}
