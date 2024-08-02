<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

interface RequestDecoderFactoryInterface
{
    public function createDecoder(Request $request): ?RequestDecoderInterface;
}
