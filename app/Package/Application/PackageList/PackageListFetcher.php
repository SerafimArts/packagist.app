<?php

declare(strict_types=1);

namespace App\Package\Application\PackageList;

use App\Package\Domain\PackageRepositoryInterface;

final readonly class PackageListFetcher
{
    public function __construct(
        private PackageRepositoryInterface $packages,
    ) {}

    public function getAll(): PackageList
    {
        $result = [];

        foreach ($this->packages->getAllNames() as $name) {
            $result[] = (string) $name;
        }

        return new PackageList($result);
    }
}
