<?php

declare(strict_types=1);

namespace App\Package\Application\PackageList;

final readonly class PackageList
{
    /**
     * @param list<non-empty-string> $names
     */
    public function __construct(
        public array $names = [],
    ) {}
}
