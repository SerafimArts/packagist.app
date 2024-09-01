<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\V1\PackageInfoController;

use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller\V1
 */
final readonly class PackageInfoResponseDTO
{
    /**
     * @param iterable<non-empty-string, iterable<non-empty-string, PackageVersionResponseDTO>> $packages
     */
    public function __construct(
        public iterable $packages,
    ) {}
}
