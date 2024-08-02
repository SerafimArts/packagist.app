<?php

declare(strict_types=1);

namespace Local\Token\Exception;

class KeyException extends TokenException
{
    /**
     * @var int
     */
    protected const CODE_LAST = parent::CODE_LAST;
}
