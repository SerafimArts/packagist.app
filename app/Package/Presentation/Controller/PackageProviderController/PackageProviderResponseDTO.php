<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageProviderController;

use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 */
final readonly class PackageProviderResponseDTO
{
    /**
     * @param PackageVersionResponseDTO $packages
     */
    public function __construct(
        public iterable $packages,
    ) {}
}
