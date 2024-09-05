<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageProviderController;

use App\Packagist\Application\GetPackageInfo\PackageInfo;
use App\Packagist\Domain\Package;
use App\Packagist\Presentation\Response\DTO\PackageReleaseResponseDTO;
use App\Packagist\Presentation\Response\Transformer\PackageReleaseTransformer;
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
        private PackageReleaseTransformer $releases,
    ) {}

    public function transform(mixed $entry): PackageProviderResponseDTO
    {
        $result = [];

        foreach ($entry->packages as $package) {
            $result[(string) $package->name] = $this->mapReleases($package);
        }

        return new PackageProviderResponseDTO($result);
    }

    /**
     * @return iterable<non-empty-string, PackageReleaseResponseDTO>
     */
    private function mapReleases(Package $package): iterable
    {
        foreach ($package->releases as $release) {
            yield (string) $release->version => $this->releases->transform($release);
        }
    }
}
