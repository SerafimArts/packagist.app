<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageMetaController;

use App\Packagist\Application\GetPackageInfo\PackageInfo;
use App\Packagist\Domain\Package;
use App\Packagist\Domain\ReleasesSet;
use App\Packagist\Presentation\Response\DTO\PackageReleaseResponseDTO;
use App\Packagist\Presentation\Response\Transformer\PackageReleaseTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller\V2
 *
 * @template-extends ResponseTransformer<PackageInfo, PackageInfoResponseDTO>
 */
final readonly class PackageInfoResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private PackageReleaseTransformer $releases,
    ) {}

    public function transform(mixed $entry): PackageInfoResponseDTO
    {
        $result = [];

        foreach ($entry->packages as $package) {
            $result[(string) $package->name] = $this->mapReleases($package, $entry->dev);
        }

        return new PackageInfoResponseDTO($result);
    }

    private function getPackageReleases(Package $package, ?bool $dev): ReleasesSet
    {
        return match ($dev) {
            true => $package->releases->dev,
            false => $package->releases->released,
            default => $package->releases,
        };
    }

    /**
     * @return iterable<array-key, PackageReleaseResponseDTO>
     */
    private function mapReleases(Package $package, ?bool $dev): iterable
    {
        $previous = null;

        foreach ($this->getPackageReleases($package, $dev) as $version) {
            yield $this->releases->transform($version, $previous);

            $previous = $version;
        }
    }
}
