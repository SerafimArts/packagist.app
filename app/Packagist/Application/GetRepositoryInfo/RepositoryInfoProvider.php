<?php

declare(strict_types=1);

namespace App\Packagist\Application\GetRepositoryInfo;

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
    private const string PACKAGE_BATCH_NOTIFY_ROUTE = 'package.downloads';

    /**
     * @var non-empty-string
     */
    private const string PACKAGES_LIST_ROUTE = 'package.list';

    public function __construct(
        private UrlGeneratorInterface $generator,
    ) {}

    /**
     * @param non-empty-string $name
     * @param array<non-empty-string, string> $parameters
     *
     * @return non-empty-string
     */
    private function url(string $name, array $parameters = []): string
    {
        return $this->generator->generate(
            name: $name,
            parameters: $parameters,
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }

    /**
     * @return non-empty-string
     */
    private function getMetadataTemplateUrl(): string
    {
        $result = $this->url(self::PACKAGE_METADATA_ROUTE, [
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
        $result = $this->url(self::PACKAGE_PROVIDER_ROUTE, [
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
        return $this->url(self::PACKAGES_LIST_ROUTE);
    }

    /**
     * @return non-empty-string
     */
    private function getNotifyBatchUrl(): string
    {
        return $this->url(self::PACKAGE_BATCH_NOTIFY_ROUTE);
    }

    public function get(): RepositoryInfo
    {
        return new RepositoryInfo(
            metadataTemplateUrl: $this->getMetadataTemplateUrl(),
            providersTemplateUrl: $this->getProvidersTemplateUrl(),
            notifyBatchUrl: $this->getNotifyBatchUrl(),
            listUrl: $this->getListUrl(),
        );
    }
}
