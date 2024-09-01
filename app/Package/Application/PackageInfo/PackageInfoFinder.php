<?php

declare(strict_types=1);

namespace App\Package\Application\PackageInfo;

use App\Package\Domain\Name;
use App\Package\Domain\Name\NameParser;
use App\Package\Domain\PackageRepositoryInterface;

final readonly class PackageInfoFinder
{
    public function __construct(
        private NameParser $parser,
        private PackageRepositoryInterface $packages,
    ) {}

    public function getByName(Name $name): PackageInfo
    {
        $package = $this->packages->findByName($name);

        if ($package === null) {
            return new PackageInfo();
        }

        return new PackageInfo([$package]);
    }

    public function getByNameString(string $name): PackageInfo
    {
        if ($name === '') {
            return new PackageInfo();
        }

        return $this->getByName($this->parser->parse($name));
    }
}
