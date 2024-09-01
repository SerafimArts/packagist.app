<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\V2\PackageInfoController;

use App\Package\Application\PackageInfo\PackageInfo;
use App\Package\Domain\Package;
use App\Package\Domain\Version\PackageVersionsSet;
use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;
use App\Package\Presentation\Response\Transformer\PackageVersionTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller\V2
 *
 * @template-extends ResponseTransformer<PackageInfo, PackageInfoResponseDTO>
 */
final readonly class PackageInfoResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private PackageVersionTransformer $versions,
    ) {}

    public function transform(mixed $entry, ?bool $dev = null): PackageInfoResponseDTO
    {
        $result = [];

        foreach ($entry->packages as $package) {
            $result[(string) $package->name] = $this->mapVersions($package, $dev);
        }

        return new PackageInfoResponseDTO($result);
    }

    private function getPackageVersions(Package $package, ?bool $dev): PackageVersionsSet
    {
        return match ($dev) {
            true => $package->versions->dev,
            false => $package->versions->released,
            default => $package->versions,
        };
    }

    /**
     * @return iterable<array-key, PackageVersionResponseDTO>
     */
    private function mapVersions(Package $package, ?bool $dev): iterable
    {
        $previous = null;

        foreach ($this->getPackageVersions($package, $dev) as $version) {
            yield $this->versions->transform($version, $previous);

            $previous = $version;
        }
    }
}
