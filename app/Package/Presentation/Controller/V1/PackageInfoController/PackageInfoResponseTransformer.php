<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\V1\PackageInfoController;

use App\Package\Domain\Package;
use App\Package\Domain\Version\PackageVersionsSet;
use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;
use App\Package\Presentation\Response\Transformer\PackageVersionTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller\V1
 *
 * @template-extends ResponseTransformer<Package, PackageInfoResponseDTO>
 */
final readonly class PackageInfoResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private PackageVersionTransformer $versions,
    ) {}

    public function transform(mixed $entry, ?bool $dev = null): PackageInfoResponseDTO
    {
        return new PackageInfoResponseDTO(
            packages: [
                (string) $entry->credentials => $this->mapVersions($entry, $dev),
            ]
        );
    }

    private function getPackageVersions(Package $package, ?bool $dev = null): PackageVersionsSet
    {
        $versions = match ($dev) {
            true => $package->versions->dev,
            false => $package->versions->released,
            default => $package->versions,
        };

        return $versions->withSourceOrDist;
    }

    /**
     * @return iterable<array-key, PackageVersionResponseDTO>
     */
    private function mapVersions(Package $package, ?bool $dev = null): iterable
    {
        foreach ($this->getPackageVersions($package, $dev) as $version) {
            yield $version->version => $this->versions->transform($version);
        }
    }
}
