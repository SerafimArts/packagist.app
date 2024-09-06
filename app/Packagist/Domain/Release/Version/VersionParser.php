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

    private function parseStableVersion(string $version): ?ParsedVersionInfo
    {
        try {
            $result = $this->semver->normalize($version);
        } catch (\Throwable) {
            return null;
        }

        return new ParsedVersionInfo(
            version: new Version($version),
            normalized: new Version($result),
        );
    }

    private function parseDevVersion(string $version): ?ParsedVersionInfo
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
            return new ParsedVersionInfo(
                version: new Version($version),
                normalized: new Version('dev-' . $version),
            );
        }

        $prefix = \str_starts_with($version, 'v') ? 'v' : '';

        return new ParsedVersionInfo(
            version: new Version($version),
            normalized: new Version(
                value: $prefix . \preg_replace('{(\.9{7})+}', '.x', $normalized),
            ),
        );
    }

    public function parse(string $version, bool $release = false): ?ParsedVersionInfo
    {
        if ($release) {
            return $this->parseStableVersion($version);
        }

        return $this->parseDevVersion($version);
    }
}
