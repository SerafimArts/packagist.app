<?php

declare(strict_types=1);

namespace App\Packagist\Presentation\Controller\PackageListController;

use App\Packagist\Application\GetPackageList\PackageList;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Packagist\Presentation\Controller
 *
 * @template-extends ResponseTransformer<PackageList, PackageListResponseDTO>
 */
final readonly class PackageListResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): PackageListResponseDTO
    {
        return new PackageListResponseDTO(
            names: $entry->names,
        );
    }
}
