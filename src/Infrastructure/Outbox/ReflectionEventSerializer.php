<?php

declare(strict_types=1);

namespace Infrastructure\Outbox;

use Application\Port\Outbox\EventSerializerInterface;
use BackedEnum;
use DateTimeImmutable;
use DateTimeInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use Override;

final class ReflectionEventSerializer implements EventSerializerInterface
{
    #[Override]
    public function serialize(object $event): array
    {
        $payload = [];
        $reflection = new ReflectionClass($event);

        foreach ($reflection->getProperties() as $property) {
            $payload[$property->getName()] = $this->normalizeValue($property->getValue($event));
        }

        /** @var array<string, mixed> $payload */
        return $payload;
    }

    #[Override]
    public function deserialize(string $eventClass, array $payload): object
    {
        $reflection = new ReflectionClass($eventClass);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $arguments = [];

        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (array_key_exists($name, $payload)) {
                $arguments[] = $this->denormalizeValue($parameter, $payload[$name]);

                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[] = $parameter->getDefaultValue();

                continue;
            }

            throw new RuntimeException(sprintf(
                'Missing payload key "%s" for event "%s".',
                $name,
                $eventClass,
            ));
        }

        return $reflection->newInstanceArgs($arguments);
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        if (is_array($value)) {
            $normalized = [];

            foreach ($value as $key => $item) {
                $normalized[$key] = $this->normalizeValue($item);
            }

            return $normalized;
        }

        return $value;
    }

    private function denormalizeValue(ReflectionParameter $parameter, mixed $value): mixed
    {
        $type = $parameter->getType();

        if (! $type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return $value;
        }

        $typeName = $type->getName();

        if ($value === null) {
            return null;
        }

        if (is_a($typeName, DateTimeImmutable::class, true)) {
            return new DateTimeImmutable((string) $value);
        }

        if (enum_exists($typeName) && is_subclass_of($typeName, BackedEnum::class)) {
            /** @var class-string<BackedEnum> $typeName */
            return $typeName::from($value);
        }

        return $value;
    }
}
