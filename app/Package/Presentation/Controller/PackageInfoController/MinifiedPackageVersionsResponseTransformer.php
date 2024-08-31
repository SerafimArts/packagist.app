<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageInfoController;

use App\Package\Domain\Package;
use App\Package\Presentation\Response\DTO\MinifiedPackageVersionResponseDTO;
use App\Package\Presentation\Response\Transformer\MinifiedPackageVersionTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 *
 * @template-extends ResponseTransformer<Package, MinifiedPackageVersionsResponseDTO>
 */
final readonly class MinifiedPackageVersionsResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private MinifiedPackageVersionTransformer $versions,
    ) {}

    public function transform(mixed $entry, ?bool $dev = null): MinifiedPackageVersionsResponseDTO
    {
        return new MinifiedPackageVersionsResponseDTO(
            packages: [
                (string) $entry->credentials => $this->mapVersions($entry, $dev),
            ]
        );
    }

    /**
     * @return iterable<array-key, MinifiedPackageVersionResponseDTO>
     */
    private function mapVersions(Package $package, ?bool $dev = null): iterable
    {
        $versions = match ($dev) {
            true => $package->versions->dev,
            false => $package->versions->released,
            default => $package->versions,
        };

        $previous = null;

        foreach ($versions as $version) {
            yield $this->versions->transform($version, $previous);

            $previous = $version;
        }
    }
}
