<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackagesListController;

use JMS\Serializer\Annotation as Serializer;

#[Serializer\VirtualProperty(name: 'packages', exp: '[]')]
#[Serializer\VirtualProperty(
    name: 'warning',
    exp: '"Support for Composer 1 is deprecated and some packages will not be available. '
        . 'You should upgrade to Composer 2. '
        . 'See https://blog.packagist.com/deprecating-composer-1-support/"',
)]
#[Serializer\VirtualProperty(name: 'warning-versions', exp: '"<1.99"')]
final readonly class PackagesListResponseDTO
{
    public function __construct(
        #[Serializer\SerializedName('metadata-url')]
        public string $metadataUrl,
    ) {}
}
