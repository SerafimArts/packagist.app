<?php

declare(strict_types=1);

namespace App\Packagist\Application\GetPackageList;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @api
 */
#[AsMessageHandler]
final readonly class GetPackageListQueryHandler
{
    public function __construct(
        private PackageListFetcher $fetcher,
    ) {}

    public function __invoke(GetPackageListQuery $query): PackageList
    {
        return $this->fetcher->getAll();
    }
}
