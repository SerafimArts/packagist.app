<?php

declare(strict_types=1);

namespace App\Package\Presentation\Response\Transformer;

use App\Package\Domain\Reference\SourceReference;
use App\Package\Presentation\Response\DTO\SourceReferenceResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<SourceReference, SourceReferenceResponseDTO>
 */
final readonly class SourceReferenceResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): SourceReferenceResponseDTO
    {
        return new SourceReferenceResponseDTO(
            type: $entry->type,
            url: $entry->url,
            hash: $entry->hash,
        );
    }
}
