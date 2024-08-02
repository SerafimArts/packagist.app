<?php

declare(strict_types=1);

namespace Local\HttpData\Attribute;

abstract class RequestDTOAttribute
{
    /**
     * In the case that this parameter is not {@see null}, then the target entry
     * will be unpacked into an object of the specified class.
     *
     * This behavior can be useful if an interface is used as the
     * required type-hint:
     *
     * ```
     * public function exampleAction(
     *      #[RequestDTO(as: ExampleValueObject::class)] ExampleValueObjectInterface $vo
     * )
     * ```
     */
    public readonly ?string $as;

    /**
     * @param class-string|null $as
     */
    public function __construct(?string $as = null)
    {
        $this->as = $as;
    }
}
