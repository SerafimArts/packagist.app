<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\Transformer;

use App\Package\Domain\Version\ComputedChangeSet;
use App\Package\Domain\Version\PackageVersion;
use App\Package\Presentation\Response\DTO\PackageVersionResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;
use Composer\Semver\VersionParser;

/**
 * @template-extends ResponseTransformer<PackageVersion, PackageVersionResponseDTO>
 */
final readonly class PackageVersionTransformer extends ResponseTransformer
{
    public function __construct(
        private SourceReferenceResponseTransformer $sources,
        private DistReferenceResponseTransformer $dists,
        private VersionParser $semver = new VersionParser(),
    ) {}

    private function normalizeVersion(PackageVersion $version): string
    {
        $result = $version->version;

        if (\str_starts_with($result, 'dev-')) {
            return $result;
        }

        if (\str_ends_with($result, '.x')) {
            try {
                return $this->semver->normalize($result . '-dev');
            } catch (\Throwable) {
                return 'dev-' . $result;
            }
        }

        try {
            return $this->semver->normalize($result);
        } catch (\Throwable) {
            return 'dev-' . $result;
        }
    }

    private function formatVersion(PackageVersion $version): string
    {
        try {
            $this->semver->normalize($version->version);

            return $version->version;
        } catch (\Throwable) {
            return $version->version . '-dev';
        }
    }

    public function transform(mixed $entry, ?PackageVersion $prev = null): PackageVersionResponseDTO
    {
        $changeSet = new ComputedChangeSet($entry, $prev);

        return new PackageVersionResponseDTO(
            name: $changeSet->fetchNameIfChanged(),
            description: $changeSet->fetchDescriptionIfChanged(),
            keywords: $changeSet->fetchKeywordsIfChanged(),
            homepage: $changeSet->fetchHomepageIfChanged(),
            license: $changeSet->fetchLicensesIfChanged(),
            authors: $changeSet->fetchAuthorsIfChanged(),
            type: $changeSet->fetchTypeIfChanged(),
            funding: $changeSet->fetchFundingIfChanged(),
            source: $this->sources->optional($entry->source),
            dist: $this->dists->optional($entry->dist),
            version: $this->formatVersion($entry),
            versionNormalized: $this->normalizeVersion($entry),
            updatedAt: $entry->updatedAt ?? $entry->createdAt,
        );
    }
}
