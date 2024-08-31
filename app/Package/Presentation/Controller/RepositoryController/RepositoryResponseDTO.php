<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\RepositoryController;

use JMS\Serializer\Annotation as Serializer;

#[Serializer\VirtualProperty(name: 'packages', exp: '[]')]
#[Serializer\VirtualProperty(
    name: 'warning',
    exp: '"Support for Composer 1 is deprecated and some packages will not be available. '
        . 'You should upgrade to Composer 2. '
        . 'See https://blog.packagist.com/deprecating-composer-1-support/"',
)]
#[Serializer\VirtualProperty(name: 'warning-versions', exp: '"<1.99"')]
final readonly class RepositoryResponseDTO
{
    public function __construct(
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('notify-batch')]
        #[Serializer\Exclude(if: 'object.notifyBatchUrl === null')]
        public ?string $notifyBatchUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('providers-url')]
        #[Serializer\Exclude(if: 'object.providersUrl === null')]
        public ?string $providersUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('metadata-url')]
        #[Serializer\Exclude(if: 'object.metadataUrl === null')]
        public ?string $metadataUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('metadata-changes-url')]
        #[Serializer\Exclude(if: 'object.metadataChangesUrl === null')]
        public ?string $metadataChangesUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('search')]
        #[Serializer\Exclude(if: 'object.searchUrl === null')]
        public ?string $searchUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('list')]
        #[Serializer\Exclude(if: 'object.listUrl === null')]
        public ?string $listUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[Serializer\SerializedName('providers-api')]
        #[Serializer\Exclude(if: 'object.providersApiUrl === null')]
        public ?string $providersApiUrl = null,
        #[Serializer\SerializedName('security-advisories')]
        #[Serializer\Exclude(if: 'object.securityAdvisories === null')]
        public ?RepositorySecurityAdvisoriesResponseDTO $securityAdvisories = null,
    ) {}
}
