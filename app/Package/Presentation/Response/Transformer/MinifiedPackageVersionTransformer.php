<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\Transformer;

use App\Package\Domain\PackageVersion;
use App\Package\Domain\Version\ComputedChangeSet;
use App\Package\Presentation\Response\DTO\MinifiedPackageVersionResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;
use Composer\Semver\VersionParser;

/**
 * @template-extends ResponseTransformer<PackageVersion, MinifiedPackageVersionResponseDTO>
 */
final readonly class MinifiedPackageVersionTransformer extends ResponseTransformer
{
    public function __construct(
        private SourceReferenceResponseTransformer $sources,
        private DistReferenceResponseTransformer $dists,
        private VersionParser $semver = new VersionParser(),
    ) {}

    public function transform(mixed $entry, ?PackageVersion $prev = null): MinifiedPackageVersionResponseDTO
    {
        try {
            $normalized = $this->semver->normalize($entry->version);
        } catch (\Throwable) {
            $normalized = $entry->version;
        }

        $changeSet = new ComputedChangeSet($entry, $prev);

        return new MinifiedPackageVersionResponseDTO(
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
            version: $entry->version,
            versionNormalized: $normalized,
            updatedAt: $entry->updatedAt ?? $entry->createdAt,
        );
    }
}
