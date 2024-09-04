<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageDownloadsController;

use JMS\Serializer\Annotation as Serializer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 */
final readonly class PackageDownloadsRequestDTO
{
    /**
     * @param list<DownloadedPackageInfoRequestDTO> $downloads
     */
    public function __construct(
        #[Serializer\Type('array<' . DownloadedPackageInfoRequestDTO::class . '>')]
        public array $downloads = [],
    ) {}
}
