<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\PackageFinder;
use App\Package\Presentation\Controller\PackageVersionsController\PackageVersionsResponseDTO;
use App\Package\Presentation\Controller\PackageVersionsController\PackageVersionsResponseTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/package/{package}.json', name: 'package', methods: Request::METHOD_GET, stateless: true)]
#[Route('/package/{package}~dev.json', name: 'package.dev', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackageVersionsController
{
    public function __construct(
        private PackageVersionsResponseTransformer $response,
        private PackageFinder $finder,
    ) {}

    public function __invoke(string $package): PackageVersionsResponseDTO
    {
        $instance = $this->finder->findByPackageString($package);

        return $this->response->transform(
            entry: $instance,
        );
    }
}
