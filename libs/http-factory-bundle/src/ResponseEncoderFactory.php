<?php

declare(strict_types=1);

namespace Local\HttpFactory;

use Symfony\Component\HttpFoundation\Request;

final readonly class ResponseEncoderFactory implements ResponseEncoderFactoryInterface
{
    /**
     * @var list<ResponseMatcherInterface>
     */
    private array $encoders;

    /**
     * @param iterable<array-key, ResponseMatcherInterface> $encoders
     */
    public function __construct(iterable $encoders = [])
    {
        $this->encoders = \array_values([...$encoders]);
    }

    public function createEncoder(Request $request): ?ResponseEncoderInterface
    {
        foreach ($this->encoders as $factory) {
            if ($factory->isAcceptable($request)) {
                return $factory;
            }
        }

        return null;
    }
}
