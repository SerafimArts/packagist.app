<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackageVersionsController;

use App\Package\Domain\Package;
use App\Package\Presentation\Response\Transformer\PackageVersionTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Package\Presentation\Controller
 *
 * @template-extends ResponseTransformer<Package, PackageVersionsResponseDTO>
 */
final readonly class PackageVersionsResponseTransformer extends ResponseTransformer
{
    public function __construct(
        private PackageVersionTransformer $versions,
    ) {}

    public function transform(mixed $entry): PackageVersionsResponseDTO
    {
        return new PackageVersionsResponseDTO(
            packages: [
                (string) $entry->credentials => $this->versions->mapToArray(
                    entries: $entry->versions,
                ),
            ]
        );
    }
}
