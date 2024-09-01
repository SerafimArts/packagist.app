<?php

declare(strict_types=1);

namespace App\Package\Application\PackageInfo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @api
 */
#[AsMessageHandler]
final readonly class GetPackageByNameStringQueryHandler
{
    public function __construct(
        private PackageInfoFinder $finder,
    ) {}

    public function __invoke(GetPackageByNameStringQuery $query): PackageInfo
    {
        return $this->finder->getByNameString(
            name: $query->name,
            dev: $query->dev,
        );
    }
}
