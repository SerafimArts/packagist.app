<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageVersionsController;

use App\Package\Presentation\Response\DTO\MinifiedPackageVersionResponseDTO;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 */
#[VirtualProperty(name: 'minified', exp: '"composer/2.0"')]
#[VirtualProperty(name: 'security-advisories', exp: '[]')]
final readonly class MinifiedPackageVersionsResponseDTO
{
    /**
     * @param iterable<non-empty-string, iterable<non-empty-string, list<MinifiedPackageVersionResponseDTO>>> $packages
     */
    public function __construct(
        public iterable $packages,
    ) {}
}
