<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

final readonly class RequestDecoderFactory implements RequestDecoderFactoryInterface
{
    /**
     * @var list<RequestMatcherInterface>
     */
    private array $decoders;

    /**
     * @param iterable<array-key, RequestMatcherInterface> $decoders
     */
    public function __construct(iterable $decoders = [])
    {
        $this->decoders = \array_values([...$decoders]);
    }

    public function createDecoder(Request $request): ?RequestDecoderInterface
    {
        foreach ($this->decoders as $decoder) {
            if ($decoder->isProvides($request)) {
                return $decoder;
            }
        }

        return null;
    }
}
