<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\DTO\Packagist;

use App\Shared\Presentation\Response\DTO\FailureResponse\ExceptionErrorResponseDTO;
use JMS\Serializer\Annotation\SkipWhenEmpty;
use JMS\Serializer\Annotation\VirtualProperty;

#[VirtualProperty(name: 'status', exp: '"error"')]
final readonly class FailureResponseDTO
{
    /**
     * @param ExceptionErrorResponseDTO $debug
     */
    public function __construct(
        public string $message,
        #[SkipWhenEmpty]
        public array $debug = [],
    ) {}
}
