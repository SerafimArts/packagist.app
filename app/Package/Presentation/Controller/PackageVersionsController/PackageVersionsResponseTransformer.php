<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageVersionsController;

use App\Package\Domain\Package;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 *
 * @template-extends ResponseTransformer<Package, PackageVersionsResponseDTO>
 */
final readonly class PackageVersionsResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): PackageVersionsResponseDTO
    {
        return new PackageVersionsResponseDTO(
            packages: [],
        );
    }
}
