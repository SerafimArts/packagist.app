<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageMetaController;

use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller\V2
 */
#[VirtualProperty(name: 'minified', exp: '"composer/2.0"')]
#[VirtualProperty(name: 'security-advisories', exp: '[]')]
final readonly class PackageInfoResponseDTO
{
    /**
     * @param PackageVersionResponseDTO $packages
     */
    public function __construct(
        public iterable $packages,
    ) {}
}
