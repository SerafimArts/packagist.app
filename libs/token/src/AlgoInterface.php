<?php

declare(strict_types=1);

namespace Local\Token;

interface AlgoInterface
{
    /**
     * @return non-empty-string
     */
    public function getName(): string;
}
