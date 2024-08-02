<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\DTO\ErrorResponse;

final class ExceptionErrorResponseDTO
{
    /**
     * @psalm-taint-sink file $file
     * @param class-string $class
     * @param list<string> $trace
     */
    public function __construct(
        public string $message,
        public string $class,
        public string $file,
        public int|string $code,
        public int $line,
        public array $trace = [],
    ) {}
}
