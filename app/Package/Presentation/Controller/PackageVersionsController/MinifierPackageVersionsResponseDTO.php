<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageVersionsController;

use JMS\Serializer\Annotation as Serializer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 */
#[Serializer\VirtualProperty(name: 'minified', exp: '"composer/2.0"')]
final readonly class MinifierPackageVersionsResponseDTO extends PackageVersionsResponseDTO
{
    /**
     * @param iterable<non-empty-string, object> $packages
     */
    public function __construct(
        iterable $packages,
    ) {
        parent::__construct($packages);
    }
}
