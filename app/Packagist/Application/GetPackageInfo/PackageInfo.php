<?php

declare(strict_types=1);

namespace App\Packagist\Application\GetPackageInfo;

use App\Packagist\Domain\Package;

final readonly class PackageInfo
{
    /**
     * @param list<Package> $packages
     */
    public function __construct(
        public array $packages = [],
        public ?bool $dev = null,
    ) {}
}
