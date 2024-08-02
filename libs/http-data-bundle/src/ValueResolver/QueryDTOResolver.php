<?php

declare(strict_types=1);

namespace Local\HttpData\ValueResolver;

use Local\HttpData\Attribute\MapQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class QueryDTOResolver extends DTOResolver
{
    private function findBodyAttribute(ArgumentMetadata $argument): ?MapQuery
    {
        foreach ($argument->getAttributes(MapQuery::class, ArgumentMetadata::IS_INSTANCEOF) as $attribute) {
            /** @var MapQuery */
            return $attribute;
        }

        return null;
    }

    protected function create(Request $request, ArgumentMetadata $argument): ?object
    {
        // Lookup for #[Query] attribute
        $attribute = $this->findBodyAttribute($argument);

        if (!$attribute instanceof MapQuery) {
            return null;
        }

        return $this->hydrate(
            data: $request->query->all(),
            argument: $argument,
            attribute: $attribute,
        );
    }
}
