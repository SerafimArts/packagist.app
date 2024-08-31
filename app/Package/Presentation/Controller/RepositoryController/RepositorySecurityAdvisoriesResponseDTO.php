<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\RepositoryController;

use JMS\Serializer\Annotation as Serializer;

final readonly class RepositorySecurityAdvisoriesResponseDTO
{
    public function __construct(
        public bool $metadata = true,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('api-url')]
        #[Serializer\Exclude(if: 'object.apiUrl === null')]
        public ?string $apiUrl = null,
    ) {}
}
