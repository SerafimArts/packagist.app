<?php

declare(strict_types=1);

namespace Local\Token\Exception;

class KeyNotFoundException extends InvalidKeyException
{
    /**
     * @var int
     *
     * @final
     */
    public const ERROR_CODE_KEY_NOT_FOUND = 0x01 + parent::CODE_LAST;

    /**
     * @var int
     */
    protected const CODE_LAST = self::ERROR_CODE_KEY_NOT_FOUND;

    public static function fromInvalidPathname(string $pathname, ?\Throwable $prev = null): self
    {
        $message = \sprintf('Key file "%s" not found', $pathname);

        return new static($message, self::ERROR_CODE_KEY_NOT_FOUND, $prev);
    }
}
