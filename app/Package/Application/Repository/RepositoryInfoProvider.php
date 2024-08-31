<?php

declare(strict_types=1);

namespace App\Package\Application\Repository;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Contains information about all packages.
 */
final readonly class RepositoryInfoProvider
{
    public function __construct(
        private UrlGeneratorInterface $generator,
    ) {}

    /**
     * Required to generate a real URL without escaping route parameters.
     *
     * @param non-empty-string $name
     * @param array<non-empty-string, array{non-empty-string, non-empty-string}> $parameters
     */
    private function generateUriString(string $name, array $parameters = []): string
    {
        $before = $after = [];

        foreach ($parameters as $parameter => [$placeholder, $replacement]) {
            $before[$parameter] = $placeholder;
            $after[$placeholder] = $replacement;
        }

        return \str_replace(
            search: \array_keys($after),
            replace: \array_values($after),
            subject: $this->generator->generate($name, $before),
        );
    }

    public function get(): RepositoryInfo
    {
        return new RepositoryInfo(
            metadataTemplateUrl: $this->generateUriString(
                name: 'package',
                parameters: ['package' => ['vendor/name', '%package%']],
            ),
            listUrl: $this->generateUriString(
                name: 'package.list',
            ),
        );
    }
}
