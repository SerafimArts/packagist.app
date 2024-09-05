<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Response\DTO;

use JMS\Serializer\Annotation as Serializer;

final readonly class PackageReleaseResponseDTO
{
    /**
     * @param non-empty-string|null $name
     * @param list<non-empty-string>|null $keywords
     * @param list<non-empty-string>|null $license
     * @param list<mixed>|null $authors TODO
     * @param non-empty-string|null $type
     * @param list<mixed>|null $funding TODO
     * @param non-empty-string $version
     * @param non-empty-string $versionNormalized
     */
    public function __construct(
        #[Serializer\Exclude(if: 'object.name === null')]
        public ?string $name,
        #[Serializer\Exclude(if: 'object.description === null')]
        public ?string $description,
        #[Serializer\Exclude(if: 'object.keywords === null')]
        public ?array $keywords,
        #[Serializer\Exclude(if: 'object.homepage === null')]
        public ?string $homepage,
        #[Serializer\Exclude(if: 'object.license === null')]
        public ?array $license,
        #[Serializer\Exclude(if: 'object.authors === null')]
        public ?array $authors,
        #[Serializer\Exclude(if: 'object.type === null')]
        public ?string $type,
        #[Serializer\Exclude(if: 'object.funding === null')]
        public ?array $funding,
        #[Serializer\Exclude(if: 'object.source === null')]
        public ?SourceReferenceResponseDTO $source,
        #[Serializer\Exclude(if: 'object.dist === null')]
        public ?DistReferenceResponseDTO $dist,
        public string $version,
        #[Serializer\SerializedName(name: 'version_normalized')]
        public string $versionNormalized,
        #[Serializer\SerializedName(name: 'time')]
        public \DateTimeInterface $updatedAt,
    ) {}
}
