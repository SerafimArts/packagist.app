<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\Package\PackageFinder;
use App\Package\Presentation\Controller\PackageInfoController\MinifiedPackageVersionsResponseDTO;
use App\Package\Presentation\Controller\PackageInfoController\MinifiedPackageVersionsResponseTransformer;
use App\Shared\Domain\DomainException;
use App\Shared\Presentation\Exception\Http\HttpPresentationException;
use App\Shared\Presentation\Exception\PresentationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Return versions list for a package.
 */
#[AsController]
#[Route('/package/{package}.json', name: 'package', methods: Request::METHOD_GET, stateless: true)]
#[Route('/package/{package}~dev.json', name: 'package.dev', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackageInfoController
{
    public function __construct(
        private MinifiedPackageVersionsResponseTransformer $response,
        private PackageFinder $finder,
    ) {}

    public function __invoke(string $package, ?string $_route = null): MinifiedPackageVersionsResponseDTO
    {
        try {
            $instance = $this->finder->findByPackageString($package);
        } catch (DomainException $e) {
            throw PresentationException::fromDomainException($e);
        }

        if ($instance === null) {
            throw (new HttpPresentationException('404 not found, no packages here'))
                ->setHttpStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->response->transform(
            entry: $instance,
            dev: $_route === 'package.dev',
        );
    }
}
