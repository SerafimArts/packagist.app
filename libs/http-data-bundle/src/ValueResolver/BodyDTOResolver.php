<?php

declare(strict_types=1);

namespace Local\HttpData\ValueResolver;

use JMS\Serializer\ArrayTransformerInterface;
use Local\HttpData\Attribute\MapBody;
use Local\HttpFactory\Listener\RequestDecoderListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class BodyDTOResolver extends DTOResolver
{
    public function __construct(ArrayTransformerInterface $serializer)
    {
        parent::__construct($serializer);
    }

    private function findBodyAttribute(ArgumentMetadata $argument): ?MapBody
    {
        foreach ($argument->getAttributes(MapBody::class, ArgumentMetadata::IS_INSTANCEOF) as $attribute) {
            /** @var MapBody */
            return $attribute;
        }

        return null;
    }

    /**
     * @return array<array-key, mixed>
     */
    private function fetchRequestData(Request $request): array
    {
        $key = RequestDecoderListener::ATTR_DECODED_DATA;

        // Process request body in case of content is not decodable
        if (!$request->attributes->has($key)) {
            return $request->request->all();
        }

        try {
            return (array) $request->attributes->get($key, []);
        } finally {
            $request->attributes->remove($key);
        }
    }

    protected function create(Request $request, ArgumentMetadata $argument): ?object
    {
        // Lookup for #[Body] attribute
        $attribute = $this->findBodyAttribute($argument);

        if (!$attribute instanceof MapBody) {
            return null;
        }

        try {
            return $this->hydrate(
                data: $this->fetchRequestData($request),
                argument: $argument,
                attribute: $attribute,
            );
        } catch (HttpException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
