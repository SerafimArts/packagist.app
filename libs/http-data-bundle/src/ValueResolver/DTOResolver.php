<?php

declare(strict_types=1);

namespace Local\HttpData\ValueResolver;

use JMS\Serializer\ArrayTransformerInterface;
use Local\HttpData\Attribute\RequestDTOAttribute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

abstract class DTOResolver implements ValueResolverInterface
{
    /**
     * @var non-empty-string
     */
    public const string ATTR_MAPPED_DATA = 'app.mapped_data';

    public function __construct(
        protected readonly ArrayTransformerInterface $serializer,
    ) {}

    /**
     * Factory method that should return a DTO object based on the request's content-type.
     *
     * @throws \Throwable
     */
    abstract protected function create(Request $request, ArgumentMetadata $argument): ?object;

    /**
     * @return iterable<object|null>
     * @throws \Throwable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = $this->create($request, $argument);

        $request->attributes->set(self::ATTR_MAPPED_DATA, $dto);

        if ($dto === null) {
            return [];
        }

        return [$dto];
    }

    /**
     * @param array<array-key, mixed> $data
     */
    protected function hydrate(array $data, ArgumentMetadata $argument, RequestDTOAttribute $attribute): object
    {
        /** @var class-string $type */
        $type = $attribute->as ?? $argument->getType() ?? 'object';

        /** @var object */
        return $this->serializer->fromArray($data, $type);
    }
}
