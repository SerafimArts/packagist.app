<?php

declare(strict_types=1);

namespace Local\HttpData\Subscriber;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\PropertyMetadata as JMSPropertyMetadata;
use Metadata\ClassMetadata;
use Metadata\MetadataFactoryInterface;
use Metadata\PropertyMetadata;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final readonly class ValidateMissingPropertiesSubscriber implements EventSubscriberInterface
{
    /**
     * @return list<array<non-empty-string, non-empty-string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
            ],
        ];
    }

    /**
     * @api
     */
    public function onPostDeserialize(ObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!\is_object($object)) {
            return;
        }

        $reflection = new \ReflectionClass($object);

        foreach ($reflection->getProperties() as $property) {
            if ($property->isInitialized($object)) {
                continue;
            }

            $context = $event->getContext();
            $factory = $context->getMetadataFactory();

            $message = \vsprintf('Missing required [%s] property', [
                \implode('.', [
                    ...$context->getCurrentPath(),
                    $this->getPropertyName($object, $factory, $property),
                ]),
            ]);

            throw new UnprocessableEntityHttpException($message);
        }
    }

    /**
     * @return non-empty-string|null
     */
    private function getPropertyName(
        object $context,
        MetadataFactoryInterface $factory,
        \ReflectionProperty $property,
    ): ?string {
        $class = $factory->getMetadataForClass($context::class);

        if (!$class instanceof ClassMetadata) {
            return null;
        }

        $metadata = $class->propertyMetadata[$property->name] ?? null;

        /** @var non-empty-string */
        return match (true) {
            $metadata instanceof JMSPropertyMetadata => $metadata->serializedName ?? $metadata->name,
            $metadata instanceof PropertyMetadata => $metadata->name,
            default => $property->name,
        };
    }
}
