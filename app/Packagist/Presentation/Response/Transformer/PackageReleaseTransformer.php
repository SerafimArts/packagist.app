<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Response\Transformer;

use App\Packagist\Domain\Release;
use App\Packagist\Domain\Release\ComputedChangeSet;
use App\Packagist\Presentation\Response\DTO\PackageReleaseResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<Release, PackageReleaseResponseDTO>
 */
final readonly class PackageReleaseTransformer extends ResponseTransformer
{
    public function __construct(
        private SourceReferenceResponseTransformer $sources,
        private DistReferenceResponseTransformer $dists,
    ) {}

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
            version: $entry->version->value,
            versionNormalized: $entry->normalized->value,
            updatedAt: $entry->updatedAt ?? $entry->createdAt,
        );
    }
}
