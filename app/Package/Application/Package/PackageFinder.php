<?php

declare(strict_types=1);

namespace App\Package\Application\Package;

use App\Package\Domain\Credentials\CredentialsParser;
use App\Package\Domain\Package;
use App\Package\Domain\PackageRepositoryInterface;

final readonly class PackageFinder
{
    public function __construct(
        private CredentialsParser $parser,
        private PackageRepositoryInterface $packages,
    ) {}

    public function findByPackageString(string $package): ?Package
    {
        if ($package === '') {
            return null;
        }

        return $this->packages->findByCredentials(
            credentials: $this->parser->createFromPackage($package),
        );
    }
}
