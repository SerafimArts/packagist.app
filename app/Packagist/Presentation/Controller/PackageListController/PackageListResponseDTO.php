<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageListController;

use JMS\Serializer\Annotation as Serializer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 */
final readonly class PackageListResponseDTO
{
    /**
     * @param iterable<array-key, non-empty-string> $names
     */
    public function __construct(
        #[Serializer\SerializedName('packageNames')]
        public iterable $names = [],
    ) {}
}
