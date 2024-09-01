<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\PackageInfo\GetPackageByNameStringQuery;
use App\Package\Application\PackageInfo\PackageInfo;
use App\Package\Presentation\Controller\PackageProviderController\PackageProviderResponseDTO;
use App\Package\Presentation\Controller\PackageProviderController\PackageProviderResponseTransformer;
use App\Shared\Domain\Bus\QueryBusInterface;
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
#[Route('/package/provider/{package}.json', name: 'package.provider', methods: Request::METHOD_GET, stateless: true)]
#[Route('/package/provider/{package}{hash}.json', name: 'package.provider.hashed', methods: Request::METHOD_GET, stateless: true)]
final readonly class PackageProviderController
{
    public function __construct(
        private PackageProviderResponseTransformer $response,
        private QueryBusInterface $queries,
    ) {}

    /**
     * @param non-empty-string $package
     * @param non-empty-string|null $_route A builtin (by Symfony) parameter
     *        containing the name of the current route.
     */
    public function __invoke(string $package, ?string $_route = null): PackageProviderResponseDTO
    {
        try {
            $info = $this->queries->get(new GetPackageByNameStringQuery($package));

            if (!$info instanceof PackageInfo) {
                throw new HttpPresentationException(
                    message: 'An internal error occurred while fetching package provider info',
                );
            }
        } catch (DomainException $e) {
            throw PresentationException::fromDomainException($e);
        }

        if ($info->packages === []) {
            throw (new HttpPresentationException('404 not found, no packages here'))
                ->setHttpStatusCode(Response::HTTP_NOT_FOUND);
        }

        return $this->response->transform($info);
    }
}
