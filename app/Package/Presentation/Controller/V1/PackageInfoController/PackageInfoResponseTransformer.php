<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\V1\PackageInfoController;

use App\Package\Domain\Package;
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

    public function transform(mixed $entry): PackageInfoResponseDTO
    {
        return new PackageInfoResponseDTO(
            packages: [
                (string) $entry->name => $this->mapVersions($entry),
            ]
        );
    }

    /**
     * @return iterable<array-key, PackageVersionResponseDTO>
     */
    private function mapVersions(Package $package): iterable
    {
        foreach ($package->versions as $version) {
            yield $version->version => $this->versions->transform($version);
        }
    }
}
