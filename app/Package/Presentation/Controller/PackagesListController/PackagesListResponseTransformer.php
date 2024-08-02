<?php

declare(strict_types=1);

namespace App\Package\Presentation\Controller\PackagesListController;

use App\Package\Application\Metadata\PackagesListInfo;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal App\Account\Presentation\Controller
 *
 * @template-extends ResponseTransformer<PackagesListInfo, PackagesListResponseDTO>
 */
final readonly class PackagesListResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): PackagesListResponseDTO
    {
        return new PackagesListResponseDTO(
            metadataUrl: \urldecode((string) $entry->metadata),
        );
    }
}
