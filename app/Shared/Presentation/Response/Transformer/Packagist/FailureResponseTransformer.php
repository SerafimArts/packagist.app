<?php

declare(strict_types=1);

namespace App\Shared\Presentation\Response\Transformer\Packagist;

use App\Shared\Presentation\Exception\PublicMessageProviderInterface;
use App\Shared\Presentation\Response\DTO\FailureResponse\ExceptionErrorResponseDTO;
use App\Shared\Presentation\Response\DTO\Packagist\FailureResponseDTO;
use App\Shared\Presentation\Response\Transformer\FailureResponseTransformer\ExceptionErrorResponseTransformer;
use App\Shared\Presentation\Response\Transformer\ResponseTransformer;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ExceptionInterface as ValidationExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

/**
 * @api
 *
 * @template-extends ResponseTransformer<\Throwable, FailureResponseDTO<mixed>>
 */
final readonly class FailureResponseTransformer extends ResponseTransformer
{
    /**
     * @var non-empty-string
     */
    private const string DEFAULT_ERROR_MESSAGE = 'An error occurred while processing request';

    public function __construct(
        private ExceptionErrorResponseTransformer $transformer,
        private bool $debug,
    ) {}

    public function transform(mixed $entry): FailureResponseDTO
    {
        return new FailureResponseDTO(
            message: $this->getMessage($entry),
            debug: $this->getDebug($entry),
        );
    }

    /**
     * @return list<ExceptionErrorResponseDTO>
     */
    private function getDebug(\Throwable $e): array
    {
        if ($this->debug) {
            return $this->transformer->transform($e);
        }

        return [];
    }

    private function getMessageFromValidation(ValidationFailedException $e): string
    {
        $result = [];

        foreach ($e->getViolations() as $violation) {
            $result[] = (string) $violation->getMessage();
        }

        return \implode('; ', $result);
    }

    private function getMessage(\Throwable $e): string
    {
        return match (true) {
            $e instanceof ValidationFailedException => $this->getMessageFromValidation($e),
            $e instanceof PublicMessageProviderInterface => $e->getPublicMessage(),
            $e instanceof ValidationExceptionInterface,
            $e instanceof HttpExceptionInterface => $e->getMessage(),
            default => self::DEFAULT_ERROR_MESSAGE,
        };
    }
}
