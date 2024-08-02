<?php

declare(strict_types=1);

namespace Local\HttpFactory;

interface RequestDecoderInterface
{
    /**
     * @return object|array<array-key, mixed>
     */
    public function decode(string $payload): object|array;
}
