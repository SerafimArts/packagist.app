<?php

declare(strict_types=1);

namespace App\Package\Application\PackageInfo;

use App\Package\Domain\Name\NameParser;
use App\Package\Domain\PackageRepositoryInterface;

final readonly class PackageInfoFinder
{
    public function __construct(
        private NameParser $parser,
        private PackageRepositoryInterface $packages,
    ) {}

    public function getByNameString(string $name): PackageInfo
    {
        if ($name === '') {
            return new PackageInfo(null);
        }

        return new PackageInfo(
            package: $this->packages->findByName(
                name: $this->parser->parse($name),
            ),
        );
    }
}
