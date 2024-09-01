<?php

declare(strict_types=1);

namespace Local\UserAgent;

use Composer\Pcre\Preg;

/**
 * Note: The main code was taken from the original package repository.
 *
 * @link https://github.com/composer/packagist/blob/36fc31c61c4310981046ec924de5b37b6bf07ff7/src/Util/UserAgentParser.php
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Nils Adermann <naderman@naderman.de>
 */
final readonly class PackagistComposerParser implements ParserInterface
{
    /**
     * @var non-empty-string
     */
    private const string USER_AGENT_PATTERN = '#^'
        . 'Composer/(?P<composer>[a-z0-9.+-]+) '
        . '\((?P<os>[^\s;]+)[^;]*?; [^;]*?; '
        . '(?P<engine>HHVM|PHP) '
        . '(?P<php>[0-9.]+)[^;]*(?:; '
            . '(?P<http>streams|curl \d+\.\d+)'
        . '[^;)]*)?'
        . '(?:; Platform-PHP (?P<platform_php>[0-9.]+)[^;]*)?'
        . '(?P<ci>; CI)?'
        . '#i';

    public function parse(string $header): ?ComposerUserAgent
    {
        if ($header === '') {
            return null;
        }

        $matched = Preg::isMatch(self::USER_AGENT_PATTERN, $header, $matches);

        if (!$matched) {
            return null;
        }

        $matches['composer'] = self::normalizeComposerVersion($matches);

        return new ComposerUserAgent(
            composerVersion: self::parseComposerVersion($matches),
            phpVersion: self::parsePhpVersion($matches),
            platformPhpVersion: self::parsePlatformPhpVersion($matches),
            os: self::parseOperatingSystem($matches),
            httpVersion: self::parseHttpVersion($matches),
            ci: self::parseContinuousIntegrationFlag($matches),
        );
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function normalizeComposerVersion(array $matches): ?string
    {
        if (!isset($matches['composer'])) {
            return null;
        }

        $isSourceBranch = $matches['composer'] === 'source'
            || Preg::isMatch('{^[a-f0-9]{40}$}', $matches['composer']);

        if ($isSourceBranch) {
            return ComposerUserAgent::COMPOSER_VERSION_PRE_RELEASE;
        }

        return $matches['composer'];
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function parseComposerVersion(array $matches): ?string
    {
        if (($matches['composer'] ?? null) === null) {
            return null;
        }

        return Preg::replace(
            pattern: '{\+[a-f0-9]{40}}',
            replacement: '',
            subject: $matches['composer'],
        );
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function parsePhpVersion(array $matches): ?string
    {
        if (($matches['php'] ?? null) === null) {
            return null;
        }

        return self::getEnginePrefix($matches)
             . $matches['php'];
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function parsePlatformPhpVersion(array $matches): ?string
    {
        if (($matches['platform_php'] ?? null) === null) {
            return null;
        }

        return self::getEnginePrefix($matches)
             . $matches['platform_php'];
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function getEnginePrefix(array $matches): string
    {
        return \strtolower($matches['engine'] ?? '') === 'hhvm' ? 'hhvm-' : '';
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function parseOperatingSystem(array $matches): ?string
    {
        if (($matches['os'] ?? null) === null) {
            return null;
        }

        return Preg::replace(
            pattern: '{^cygwin_nt-.*}',
            replacement: 'cygwin',
            subject: \strtolower($matches['os']),
        );
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function parseHttpVersion(array $matches): ?string
    {
        if (($matches['http'] ?? null) === null) {
            return null;
        }

        return \strtolower($matches['http']);
    }

    /**
     * @param array<non-empty-string, string> $matches
     */
    private static function parseContinuousIntegrationFlag(array $matches): bool
    {
        return (bool) ($matches['ci'] ?? false);
    }
}
