<?php

declare(strict_types=1);

namespace App\Packagist\Application\GetPackageList;

final readonly class PackageList
{
    /**
     * @param list<non-empty-string> $names
     */
    public function __construct(
        public array $names = [],
    ) {}
}
