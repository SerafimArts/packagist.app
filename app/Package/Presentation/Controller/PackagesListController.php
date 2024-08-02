<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Presentation\Controller\PackagesListController\PackagesListResponseDTO;
use App\Package\Presentation\Controller\PackagesListController\PackagesListResponseTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/packages.json', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackagesListController
{
    public function __construct(
        private PackagesListResponseTransformer $response,
    ) {}

    public function __invoke(): PackagesListResponseDTO
    {
        return $this->response->transform(null);
    }
}
