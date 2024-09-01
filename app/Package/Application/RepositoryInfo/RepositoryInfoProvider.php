<?php

declare(strict_types=1);

namespace App\Package\Application\RepositoryInfo;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Contains information about all packages.
 */
final readonly class RepositoryInfoProvider
{
    /**
     * @var non-empty-string
     */
    private const string ROUTE_TEMP_PLACEHOLDER = 'DEADBEEF';

    /**
     * @var non-empty-string
     */
    private const string PACKAGE_METADATA_ROUTE = 'package.meta';

    /**
     * @var non-empty-string
     */
    private const string PACKAGE_PROVIDER_ROUTE = 'package.provider';

    /**
     * @var non-empty-string
     */
    private const string LIST_ROUTE = 'package.list';

    public function __construct(
        private UrlGeneratorInterface $generator,
    ) {}

    /**
     * @return non-empty-string
     */
    private function getMetadataTemplateUrl(): string
    {
        $result = $this->generator->generate(self::PACKAGE_METADATA_ROUTE, [
            'package' => self::ROUTE_TEMP_PLACEHOLDER,
        ]);

        return \str_replace(
            search: \urlencode(self::ROUTE_TEMP_PLACEHOLDER),
            replace: '%package%',
            subject: $result,
        );
    }

    /**
     * @return non-empty-string
     */
    private function getProvidersTemplateUrl(): string
    {
        $result = $this->generator->generate(self::PACKAGE_PROVIDER_ROUTE, [
            'package' => self::ROUTE_TEMP_PLACEHOLDER,
        ]);

        return \str_replace(
            search: \urlencode(self::ROUTE_TEMP_PLACEHOLDER),
            replace: '%package%$%hash%',
            subject: $result,
        );
    }

    /**
     * @return non-empty-string
     */
    private function getListUrl(): string
    {
        return $this->generator->generate(self::LIST_ROUTE);
    }

    public function get(): RepositoryInfo
    {
        return new RepositoryInfo(
            metadataTemplateUrl: $this->getMetadataTemplateUrl(),
            providersTemplateUrl: $this->getProvidersTemplateUrl(),
            listUrl: $this->getListUrl(),
        );
    }
}
