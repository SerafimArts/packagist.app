<?php

declare(strict_types=1);

namespace App\Packagist\Domain\Release;

use App\Packagist\Domain\Release;

final readonly class ComputedChangeSet
{
    public function __construct(
        private Release $release,
        private ?Release $previous,
    ) {}

    /**
     * @return non-empty-string|null
     */
    public function fetchNameIfChanged(): ?string
    {
        if ($this->previous === null) {
            return (string) $this->release->package->name;
        }

        return null;
    }

    public function fetchDescriptionIfChanged(): ?string
    {
        if ($this->previous === null || $this->previous->description !== $this->release->description) {
            return (string) $this->release->description;
        }

        return null;
    }

    /**
     * TODO
     *
     * @return list<non-empty-string>
     */
    public function fetchKeywordsIfChanged(): ?array
    {
        if ($this->previous === null) {
            return [];
        }

        return null;
    }

    /**
     * TODO
     */
    public function fetchHomepageIfChanged(): ?string
    {
        if ($this->previous === null) {
            return '';
        }

        return null;
    }

    /**
     * TODO
     *
     * @return list<non-empty-string>
     */
    public function fetchLicensesIfChanged(): ?array
    {
        if ($this->previous === null) {
            return [];
        }

        return null;
    }

    /**
     * TODO
     *
     * @return list<mixed>
     */
    public function fetchAuthorsIfChanged(): ?array
    {
        if ($this->previous === null) {
            return [];
        }

        return null;
    }

    /**
     * TODO
     *
     * @return non-empty-string
     */
    public function fetchTypeIfChanged(): ?string
    {
        if ($this->previous === null) {
            return 'library';
        }

        return null;
    }

    /**
     * TODO
     *
     * @return list<mixed>
     */
    public function fetchFundingIfChanged(): ?array
    {
        if ($this->previous === null) {
            return [];
        }

        return null;
    }
}
