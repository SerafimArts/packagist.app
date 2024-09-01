<?php

declare(strict_types=1);

namespace App\Package\Application\Repository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Contains information about all packages.
 */
final readonly class RepositoryInfoProvider
{
    /**
     * @var non-empty-string
     */
    private const string PACKAGE_ROUTE = 'package';

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
        $result = $this->generator->generate(self::PACKAGE_ROUTE, [
            'package' => 'VENDOR/NAME',
        ]);

        return \str_replace('VENDOR/NAME', '%package%', $result);
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
            listUrl: $this->getListUrl(),
        );
    }
}
