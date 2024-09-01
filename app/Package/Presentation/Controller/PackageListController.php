<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\PackageList\PackageListFinder;
use App\Package\Presentation\Controller\PackageListController\PackageListResponseDTO;
use App\Package\Presentation\Controller\PackageListController\PackageListResponseTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Return list of packages.
 */
#[AsController]
#[Route('/packages/list.json', name: 'package.list', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackageListController
{
    public function __construct(
        private PackageListFinder $finder,
        private PackageListResponseTransformer $response,
    ) {}

    public function __invoke(): PackageListResponseDTO
    {
        return $this->response->transform(
            entry: $this->finder->getAll(),
        );
    }
}
