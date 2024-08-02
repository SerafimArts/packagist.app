<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Translation;

use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class SwitchableTranslator implements TranslatorInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private LocaleSwitcher $switcher,
    ) {}

    /**
     * @param array<array-key, mixed> $parameters
     */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        $locale ??= $this->switcher->getLocale();
        $default = $parameters['default'] ?? null;

        $result = $this->translator->trans($id, $parameters, $domain, $locale);

        if ($result === $id && \is_string($default)) {
            return $default;
        }

        return $result;
    }

    public function getLocale(): string
    {
        return $this->switcher->getLocale();
    }
}
