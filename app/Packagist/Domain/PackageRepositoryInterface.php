<?php

declare(strict_types=1);

namespace App\Packagist\Domain;

interface PackageRepositoryInterface
{
    public function findByName(Name $name): ?Package;

    /**
     * @return iterable<array-key, Package>
     */
    public function getAll(): iterable;

    /**
     * @return iterable<array-key, Name>
     */
    public function getAllNames(): iterable;
}
