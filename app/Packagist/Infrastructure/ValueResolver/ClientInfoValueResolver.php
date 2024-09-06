<?php

declare(strict_types=1);

namespace App\Packagist\Infrastructure\ValueResolver;

use App\Packagist\Presentation\Request\DTO\ClientInfoRequestDTO;
use Local\UserAgent\ComposerUserAgent;
use Local\UserAgent\ParserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ClientInfoValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ParserInterface $parser,
    ) {}

    private function isExpectedArgument(ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        return \is_string($type)
            && \is_a($type, ClientInfoRequestDTO::class, true);
    }

    private function fetchComposerUserAgent(Request $request): ?ComposerUserAgent
    {
        $userAgent = $request->headers->get('user-agent');

        if ($userAgent === null) {
            return null;
        }

        return $this->parser->parse($userAgent);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$this->isExpectedArgument($argument)) {
            return [];
        }

        $composerUserAgent = $this->fetchComposerUserAgent($request);

        if ($composerUserAgent === null) {
            return yield new ClientInfoRequestDTO(
                composerVersion: 'unknown',
                phpVersion: 'unknown',
                operatingSystem: 'unknown',
            );
        }

        yield new ClientInfoRequestDTO(
            composerVersion: $composerUserAgent->composerVersion,
            phpVersion: $composerUserAgent->phpVersion,
            operatingSystem: $composerUserAgent->os,
            ci: $composerUserAgent->ci,
        );
    }
}
