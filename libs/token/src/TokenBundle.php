<?php

declare(strict_types=1);

namespace Local\Token;

use Local\Token\DependencyInjection\TokenExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TokenBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new TokenExtension();
    }
}