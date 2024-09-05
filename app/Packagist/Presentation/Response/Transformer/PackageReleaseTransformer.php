<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Response\Transformer;

use App\Packagist\Domain\Release;
use App\Packagist\Domain\Release\ComputedChangeSet;
use App\Packagist\Presentation\Response\DTO\PackageReleaseResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;
use Composer\Semver\VersionParser;

/**
 * @template-extends ResponseTransformer<Release, PackageReleaseResponseDTO>
 */
final readonly class PackageReleaseTransformer extends ResponseTransformer
{
    public function __construct(
        private SourceReferenceResponseTransformer $sources,
        private DistReferenceResponseTransformer $dists,
        private VersionParser $semver = new VersionParser(),
    ) {}

    private function normalizeVersion(Release $release): string
    {
        $result = (string) $release->version;

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

    private function formatVersion(Release $version): string
    {
        $result = (string) $version->version;

        try {
            $this->semver->normalize($result);

            return $result;
        } catch (\Throwable) {
            return $result . '-dev';
        }
    }

    public function transform(mixed $entry, ?Release $prev = null): PackageReleaseResponseDTO
    {
        $changeSet = new ComputedChangeSet($entry, $prev);

        return new PackageReleaseResponseDTO(
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
