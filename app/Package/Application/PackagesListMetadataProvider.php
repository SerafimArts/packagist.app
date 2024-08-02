<?php

declare(strict_types=1);

namespace App\Package\Application;

use App\Package\Application\Metadata\PackagesListInfo;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class PackagesListMetadataProvider
{
    public function __construct(
        private UrlGeneratorInterface $generator,
        private UriFactoryInterface $uri,
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

    /**
     * @param non-empty-string $name
     * @param array<non-empty-string, array{non-empty-string, non-empty-string}> $parameters
     */
    private function generateUri(string $name, array $parameters = []): UriInterface
    {
        return $this->uri->createUri($this->generateUriString(
            name: $name,
            parameters: $parameters,
        ));
    }

    public function get(): PackagesListInfo
    {
        return new PackagesListInfo(
            metadata: $this->generateUri('package', [
                'package' => ['vendor/name', '%package%'],
            ]),
        );
    }
}
