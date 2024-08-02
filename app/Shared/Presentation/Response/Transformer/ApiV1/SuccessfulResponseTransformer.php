<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Transformer\ApiV1;

use App\Shared\Presentation\Response\DTO\ApiV1\SuccessfulResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @api
 *
 * @template TInput of mixed
 * @template-extends ResponseTransformer<TInput, SuccessfulResponseDTO<TInput>>
 */
final readonly class SuccessfulResponseTransformer extends ResponseTransformer
{
    /**
     * @param TInput $entry
     *
     * @return SuccessfulResponseDTO<TInput>
     */
    public function transform(mixed $entry): SuccessfulResponseDTO
    {
        /** @var SuccessfulResponseDTO<TInput> */
        return new SuccessfulResponseDTO($entry);
    }
}
