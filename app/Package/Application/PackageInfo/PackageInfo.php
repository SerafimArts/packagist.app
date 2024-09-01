<?php

declare(strict_types=1);

namespace App\Package\Application\PackageInfo;

use App\Package\Domain\Package;

final readonly class PackageInfo
{
    public function __construct(
        public ?Package $package = null,
    ) {}
}
