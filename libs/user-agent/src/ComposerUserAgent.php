<?php

declare(strict_types=1);

namespace Local\UserAgent;

final class ComposerUserAgent
{
    /**
     * @var non-empty-string
     */
    public const string COMPOSER_VERSION_PRE_RELEASE = 'pre-1.8.5';

    /**
     * @param non-empty-string|null $composerVersion
     * @param non-empty-string|null $phpVersion
     * @param non-empty-string|null $platformPhpVersion
     * @param non-empty-string|null $os
     * @param non-empty-string|null $httpVersion
     */
    public function __construct(
        public readonly ?string $composerVersion = null,
        public readonly ?string $phpVersion = null,
        public readonly ?string $platformPhpVersion = null,
        public readonly ?string $os = null,
        public readonly ?string $httpVersion = null,
        public readonly ?bool $ci = null,
    ) {}

    /**
     * @api Referenced to {@see ComposerUserAgent::$composerMajorVersion}
     * @return non-empty-string|null
     */
    private function getComposerMajorVersion(): ?string
    {
        if (!$this->composerVersion) {
            return null;
        }

        if ($this->composerVersion === self::COMPOSER_VERSION_PRE_RELEASE) {
            return '1';
        }

        $major = \substr($this->composerVersion, 0, 1);

        return \is_numeric($major) ? $major : null;
    }

    /**
     * @var non-empty-string|null
     */
    public ?string $composerMajorVersion {
        get => $this->getComposerMajorVersion();
    }
}
