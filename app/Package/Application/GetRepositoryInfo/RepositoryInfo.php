<?php

declare(strict_types=1);

namespace App\Package\Application\GetRepositoryInfo;

final readonly class RepositoryInfo
{
    public function __construct(
        /**
         * Provides concrete package metadata template url for APIv2.
         *
         * The "template" term means that a specific package name must be
         * substituted for the value "%package%" within this url pattern.
         *
         * @var non-empty-string
         */
        public string $metadataTemplateUrl,
        /**
         * Provides concrete package metadata template url for APIv1.
         *
         * The "template" term means that a specific package name must be
         * substituted for the value "%package%" within this url pattern
         * and "%hash%" for package versions list hash.
         *
         * @var non-empty-string
         */
        public string $providersTemplateUrl,
        /**
         * Provides url to the packages list.
         *
         * @var non-empty-string
         */
        public string $listUrl,
    ) {}
}
