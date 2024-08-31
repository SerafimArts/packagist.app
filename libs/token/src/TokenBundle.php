<?php

declare(strict_types=1);

namespace Local\Token;

use Local\Token\DependencyInjection\TokenExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TokenBundle extends Bundle
{
    public function getContainerExtension(): TokenExtension
    {
        return new TokenExtension();
    }
}
