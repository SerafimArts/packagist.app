<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\Repository\RepositoryInfoProvider;
use App\Package\Presentation\Controller\RepositoryController\RepositoryResponseDTO;
use App\Package\Presentation\Controller\RepositoryController\RepositoryResponseTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Returns main metadata for all packages.
 */
#[AsController]
#[Route('/packages.json', name: 'repository', methods: Request::METHOD_GET, stateless: true)]
final readonly class RepositoryController
{
    public function __construct(
        private RepositoryResponseTransformer $response,
        private RepositoryInfoProvider $metadata,
    ) {}

    public function __invoke(Request $request): RepositoryResponseDTO
    {
        return $this->response->transform(
            entry: $this->metadata->get(),
        );
    }
}
