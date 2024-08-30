<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\PackagesListMetadataProvider;
use App\Package\Presentation\Controller\PackagesListController\PackagesListResponseDTO;
use App\Package\Presentation\Controller\PackagesListController\PackagesListResponseTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Returns main metadata for all packages.
 */
#[AsController]
#[Route('/packages.json', name: 'package.list', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackagesListController
{
    public function __construct(
        private PackagesListResponseTransformer $response,
        private PackagesListMetadataProvider $metadata,
    ) {}

    public function __invoke(): PackagesListResponseDTO
    {
        return $this->response->transform(
            entry: $this->metadata->get(),
        );
    }
}
