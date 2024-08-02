<?php

declare(strict_types=1);

namespace Local\Token\Exception;

class InvalidKeyException extends KeyException
{
    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_INTERNAL_ERROR = 0x01 + parent::CODE_LAST;

    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_EMPTY_KEY = 0x02 + parent::CODE_LAST;

    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_EMPTY_KEY_PATHNAME = 0x03 + parent::CODE_LAST;

    /**
     * @var int
     */
    protected const CODE_LAST = self::ERROR_CODE_EMPTY_KEY;

    public static function fromInternalError(string $message, ?\Throwable $prev = null): self
    {
        return new static($message, self::ERROR_CODE_INTERNAL_ERROR, $prev);
    }

    public static function fromEmptyKey(?\Throwable $prev = null): self
    {
        return new static('Signature key is empty', self::ERROR_CODE_EMPTY_KEY, $prev);
    }

    public static function fromEmptyKeyPathname(string $pathname, ?\Throwable $prev = null): self
    {
        $message = \sprintf('Signature key file "%s" is empty', $pathname);

        return new static($message, self::ERROR_CODE_EMPTY_KEY_PATHNAME, $prev);
    }
}
