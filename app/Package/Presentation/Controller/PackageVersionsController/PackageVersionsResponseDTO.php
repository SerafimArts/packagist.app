<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageVersionsController;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 */
readonly class PackageVersionsResponseDTO
{
    /**
     * @param iterable<non-empty-string, object> $packages
     */
    public function __construct(
        public iterable $packages,
    ) {}
}
