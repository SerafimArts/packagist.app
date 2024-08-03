<?php

declare(strict_types=1);

namespace App\Package\Application;

use App\Package\Domain\Credentials\CredentialsParser;
use App\Package\Domain\Package;

final readonly class PackageFinder
{
    public function __construct(
        private CredentialsParser $parser,
    ) {}

    /**
     * TODO
     */
    public function findByPackageString(string $package): ?Package
    {
        try {
            $this->parser->createFromPackage($package);
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}
