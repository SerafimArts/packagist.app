<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\PackageFinder;
use App\Package\Presentation\Controller\PackageVersionsController\PackageVersionsResponseDTO;
use App\Package\Presentation\Controller\PackageVersionsController\PackageVersionsResponseTransformer;
use App\Package\Presentation\Exception\PackageNotFoundException;
use App\Shared\Domain\Exception\DomainException;
use App\Shared\Presentation\Exception\PresentationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        try {
            $instance = $this->finder->findByPackageString($package);
        } catch (DomainException $e) {
            throw PresentationException::fromDomainException($e);
        }

        if ($instance === null) {
            throw (new PackageNotFoundException('404 not found, no packages here'))
                ->setHttpStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->response->transform(
            entry: $instance,
        );
    }
}
