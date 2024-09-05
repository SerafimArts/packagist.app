<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageProviderController;

use App\Packagist\Application\GetPackageInfo\PackageInfo;
use App\Packagist\Domain\Package;
use App\Packagist\Presentation\Response\DTO\PackageVersionResponseDTO;
use App\Packagist\Presentation\Response\Transformer\PackageVersionTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 *
 * @template-extends ResponseTransformer<PackageInfo, PackageProviderResponseDTO>
 */
final readonly class PackageProviderResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private PackageVersionTransformer $versions,
    ) {}

    public function transform(mixed $entry): PackageProviderResponseDTO
    {
        $result = [];

        foreach ($entry->packages as $package) {
            $result[(string) $package->name] = $this->mapVersions($package);
        }

        return new PackageProviderResponseDTO($result);
    }

    /**
     * @return iterable<array-key, PackageVersionResponseDTO>
     */
    private function mapVersions(Package $package): iterable
    {
        foreach ($package->releases as $version) {
            yield $version->version => $this->versions->transform($version);
        }
    }
}
