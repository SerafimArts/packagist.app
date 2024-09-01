<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller;

use App\Package\Application\PackageList\GetPackageListQuery;
use App\Package\Application\PackageList\PackageList;
use App\Package\Presentation\Controller\PackageListController\PackageListResponseDTO;
use App\Package\Presentation\Controller\PackageListController\PackageListResponseTransformer;
use App\Shared\Domain\Bus\QueryBusInterface;
use App\Shared\Presentation\Exception\Http\HttpPresentationException;
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
        private PackageListResponseTransformer $response,
        private QueryBusInterface $queries,
    ) {}

    public function __invoke(): PackageListResponseDTO
    {
        $result = $this->queries->get(new GetPackageListQuery());

        if (!$result instanceof PackageList) {
            throw new HttpPresentationException(
                message: 'An internal error occurred while fetching packages list',
            );
        }

        return $this->response->transform($result);
    }
}
