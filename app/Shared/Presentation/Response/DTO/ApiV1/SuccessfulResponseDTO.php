<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\DTO\ApiV1;

/**
 * @template TData of mixed
 * @template-extends ResponseDTO<TData>
 */
final readonly class SuccessfulResponseDTO extends ResponseDTO
{
    /**
     * @param TData|null $data
     */
    public function __construct(mixed $data = null)
    {
        parent::__construct($data);
    }
}
