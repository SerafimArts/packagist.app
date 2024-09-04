<?php

declare(strict_types=1);

namespace App\Packagist\Application\GetPackageInfo;

final readonly class GetPackageByNameStringQuery
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public ?bool $dev = null,
    ) {}
}
