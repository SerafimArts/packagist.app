<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\DTO;

use JMS\Serializer\Annotation as Serializer;

final readonly class PackageVersionResponseDTO
{
    /**
     * @param non-empty-string $name
     * @param non-empty-string $version
     * @param non-empty-string $versionNormalized
     */
    public function __construct(
        public string $name,
        public string $version,
        #[Serializer\SerializedName(name: 'version_normalized')]
        public string $versionNormalized,
    ) {}
}
