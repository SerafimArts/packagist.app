<?php

declare(strict_types=1);

namespace Local\UserAgent;

interface ParserInterface
{
    public function parse(string $header): ?ComposerUserAgent;
}
