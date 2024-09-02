<?php

declare(strict_types=1);

namespace App\Package\Application\GetRepositoryInfo;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @api
 */
#[AsMessageHandler]
final readonly class GetRepositoryInfoQueryHandler
{
    public function __construct(
        private RepositoryInfoProvider $provider,
    ) {}

    public function __invoke(GetRepositoryInfoQuery $query): RepositoryInfo
    {
        return $this->provider->get();
    }
}
