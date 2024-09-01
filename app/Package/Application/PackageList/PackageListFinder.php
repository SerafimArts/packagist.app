<?php

declare(strict_types=1);

namespace App\Package\Application\PackageList;

use App\Package\Domain\Package;
use App\Package\Domain\PackageRepositoryInterface;

final readonly class PackageListFinder
{
    public function __construct(
        private PackageRepositoryInterface $packages,
    ) {}

    /**
     * @return iterable<array-key, Package>
     */
    public function getAll(): iterable
    {
        return $this->packages->getAll();
    }
}
