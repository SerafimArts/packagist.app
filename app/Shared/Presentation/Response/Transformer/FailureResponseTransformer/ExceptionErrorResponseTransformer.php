<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Transformer\FailureResponseTransformer;

use App\Shared\Presentation\Response\DTO\FailureResponse\ExceptionErrorResponseDTO;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;

/**
 * @template-extends ResponseTransformer<\Throwable, list<ExceptionErrorResponseDTO>>
 */
final readonly class ExceptionErrorResponseTransformer extends ResponseTransformer
{
    public function transform(mixed $entry): array
    {
        $result = [];

        do {
            $result[] = new ExceptionErrorResponseDTO(
                message: $entry->getMessage(),
                class: $entry::class,
                file: $entry->getFile(),
                code: $entry->getCode(),
                line: $entry->getLine(),
                trace: $this->getTraceFromException($entry),
            );
        } while ($entry = $entry->getPrevious());

        return $result;
    }

    /**
     * @return list<non-empty-string>
     */
    private function getTraceFromException(\Throwable $e): array
    {
        $trace = $e->getTraceAsString();
        $result = [];

        foreach (\explode("\n", $trace) as $line) {
            \preg_match('/#\d+\h+(.+?\.php)\((\d+)\):.+/isum', $line, $matches);

            if (\count($matches) === 3) {
                $result[] = $matches[1] . ':' . $matches[2];
            }
        }

        return $result;
    }
}
