<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageDownloadsController;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 */
final readonly class DownloadedPackageInfoRequestDTO
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string|null $version The version field will contain the
     *        normalized representation of the version number.
     *        Note: This field is optional.
     */
    public function __construct(
        public string $name,
        public ?string $version = null,
    ) {}
}
