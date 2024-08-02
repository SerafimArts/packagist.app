<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\DTO\ApiV1;

use App\Shared\Presentation\Response\DTO\FailureResponse\ExceptionErrorResponseDTO;
use JMS\Serializer\Annotation\SkipWhenEmpty;

/**
 * @template TData of mixed
 * @template-extends ResponseDTO<TData>
 */
final readonly class FailureResponseDTO extends ResponseDTO
{
    /**
     * @param TData|null $data
     * @param ExceptionErrorResponseDTO $debug
     */
    public function __construct(
        public string $error,
        mixed $data = null,
        #[SkipWhenEmpty]
        public array $debug = [],
    ) {
        parent::__construct($data);
    }
}
