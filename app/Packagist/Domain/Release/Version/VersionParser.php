<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release\Version;

use App\Packagist\Domain\Release\Version;
use Composer\Semver\VersionParser as ComposerVersionParser;

final readonly class VersionParser
{
    private const string DEFAULT_BRANCH_ALIAS = '9999999-dev';

    public function __construct(
        private ComposerVersionParser $semver = new ComposerVersionParser(),
    ) {}

    private function parseStableVersion(string $version): ?Version
    {
        try {
            $normalized = $this->semver->normalize($version);
        } catch (\Throwable) {
            return null;
        }

        return new Version(
            value: $version,
            normalized: $normalized,
        );
    }

    private function parseDevVersion(string $version): ?Version
    {
        try {
            $normalized = $this->semver->normalizeBranch($version);

            // validate that the branch name has no weird characters conflicting with constraints
            $this->semver->parseConstraints($normalized);
        } catch (\Exception $e) {
            return null;
        }

        // Make sure branch packages have a dev flag

        if (\str_starts_with($normalized, 'dev-') || self::DEFAULT_BRANCH_ALIAS === $normalized) {
            return new Version(
                value: $version,
                normalized: 'dev-' . $version,
            );
        }

        $prefix = \str_starts_with($version, 'v') ? 'v' : '';

        return new Version(
            value: $version,
            normalized: $prefix . \preg_replace(
                pattern: '{(\.9{7})+}',
                replacement: '.x',
                subject: $normalized,
            ),
        );
    }

    public function parse(string $version, bool $release = false): ?Version
    {
        if ($release) {
            return $this->parseStableVersion($version);
        }

        return $this->parseDevVersion($version);
    }
}
