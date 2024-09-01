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

    public function getByName(Name $name, ?bool $dev = null): PackageInfo
    {
        $package = $this->packages->findByName($name);

        if ($package === null) {
            return new PackageInfo(dev: $dev);
        }

        return new PackageInfo(
            packages: [$package],
            dev: $dev,
        );
    }

    public function getByNameString(string $name, ?bool $dev = null): PackageInfo
    {
        if ($name === '') {
            return new PackageInfo(dev: $dev);
        }

        return $this->getByName($this->parser->parse($name));
    }
}
