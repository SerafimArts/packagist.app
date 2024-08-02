<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

interface ResponseEncoderFactoryInterface
{
    public function createEncoder(Request $request): ?ResponseEncoderInterface;
}
