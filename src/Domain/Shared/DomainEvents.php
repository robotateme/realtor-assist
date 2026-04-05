<?php

declare(strict_types=1);

namespace Domain\Shared;

use Closure;

final class DomainEvents
{
    /**
     * @var array<class-string<DomainEvent>, list<Closure>>
     */
    private static array $listeners = [];

    /**
     * @template TEvent of DomainEvent
     *
     * @param class-string<TEvent> $eventClass
     * @param callable(TEvent):void $listener
     */
    public static function subscribe(string $eventClass, callable $listener): void
    {
        self::$listeners[$eventClass][] = Closure::fromCallable($listener);
    }

    public static function dispatch(DomainEvent $event): void
    {
        foreach (self::$listeners[$event::class] ?? [] as $listener) {
            $listener($event);
        }
    }

    /**
     * @param iterable<DomainEvent> $events
     */
    public static function dispatchAll(iterable $events): void
    {
        foreach ($events as $event) {
            self::dispatch($event);
        }
    }

    public static function clear(): void
    {
        self::$listeners = [];
    }
}
