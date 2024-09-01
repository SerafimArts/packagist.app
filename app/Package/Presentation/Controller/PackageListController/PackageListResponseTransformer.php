<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageListController;

use App\Package\Domain\Package;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 *
 * @template-extends ResponseTransformer<iterable<array-key, Package>, PackageListResponseDTO>
 */
final readonly class PackageListResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): PackageListResponseDTO
    {
        return new PackageListResponseDTO(
            names: $this->mapAllPackageNames($entry),
        );
    }

    /**
     * @param iterable<array-key, Package> $packages
     *
     * @return iterable<array-key, non-empty-string>
     */
    private function mapAllPackageNames(iterable $packages): iterable
    {
        foreach ($packages as $package) {
            yield (string) $package->name;
        }
    }
}
