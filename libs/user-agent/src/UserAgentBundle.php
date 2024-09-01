<?php

declare(strict_types=1);

namespace Local\UserAgent;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @internal this is an internal library class, please do not use it in your code.
 * @psalm-internal Local\UserAgent
 */
final class UserAgentBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->register(ParserInterface::class)
            ->setClass(PackagistComposerParser::class);
    }
}
