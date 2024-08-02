<?php

declare(strict_types=1);

namespace App\Package\Domain;

interface PackageRepositoryInterface
{
    public function findByCredentials(Credentials $credentials): ?Package;

    /**
     * @return iterable<array-key, Package>
     */
    public function getAll(): iterable;
}
