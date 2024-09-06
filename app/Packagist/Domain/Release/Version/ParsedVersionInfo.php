<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release\Version;

use App\Packagist\Domain\Release\Version;

final readonly class ParsedVersionInfo
{
    public function __construct(
        public Version $version,
        public Version $normalized,
    ) {}
}
