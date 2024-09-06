<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\RepositoryController;

use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;

#[VirtualProperty(name: 'packages', exp: '[]')]
#[VirtualProperty(
    name: 'warning',
    exp: '"Support for Composer 1 is deprecated and some packages will not be available. '
    . 'You should upgrade to Composer 2. '
    . 'See https://blog.packagist.com/deprecating-composer-1-support/"',
)]
#[VirtualProperty(name: 'warning-versions', exp: '"<1.99"')]
final readonly class RepositoryResponseDTO
{
    public function __construct(
        /**
         * The notify-batch field allows you to specify a URL that will be
         * called every time a user installs a package. The URL can be either
         * an absolute path (that will use the same domain as the repository),
         * or a fully qualified URL.
         *
         *
         * @var non-empty-string|null
         */
        #[SerializedName('notify-batch')]
        #[Exclude(if: 'object.notifyBatchUrl === null')]
        public ?string $notifyBatchUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[SerializedName('providers-url')]
        #[Exclude(if: 'object.providersTemplateUrl === null')]
        public ?string $providersTemplateUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[SerializedName('metadata-url')]
        #[Exclude(if: 'object.metadataTemplateUrl === null')]
        public ?string $metadataTemplateUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[SerializedName('metadata-changes-url')]
        #[Exclude(if: 'object.metadataChangesUrl === null')]
        public ?string $metadataChangesUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[SerializedName('search')]
        #[Exclude(if: 'object.searchTemplateUrl === null')]
        public ?string $searchTemplateUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[SerializedName('list')]
        #[Exclude(if: 'object.listUrl === null')]
        public ?string $listUrl = null,
        /**
         * @var non-empty-string|null
         */
        #[SerializedName('providers-api')]
        #[Exclude(if: 'object.providersApiTemplateUrl === null')]
        public ?string $providersApiTemplateUrl = null,
        #[SerializedName('security-advisories')]
        #[Exclude(if: 'object.securityAdvisories === null')]
        public ?RepositorySecurityAdvisoriesResponseDTO $securityAdvisories = null,
    ) {}
}
