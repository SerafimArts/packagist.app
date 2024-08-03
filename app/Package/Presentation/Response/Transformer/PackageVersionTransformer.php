<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\Transformer;

use App\Package\Domain\PackageVersion;
use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;
use Composer\Semver\VersionParser;

/**
 * @template-extends ResponseTransformer<PackageVersion, PackageVersionResponseDTO>
 */
final readonly class PackageVersionTransformer extends ResponseTransformer
{
    public function __construct(
        private VersionParser $semver = new VersionParser(),
    ) {}

    public function transform(mixed $entry): PackageVersionResponseDTO
    {
        try {
            $normalized = $this->semver->normalize($entry->version);
        } catch (\Throwable) {
            $normalized = $entry->version;
        }

        return new PackageVersionResponseDTO(
            name: (string) $entry->package->credentials,
            version: $entry->version,
            versionNormalized: $normalized,
        );
    }
}
