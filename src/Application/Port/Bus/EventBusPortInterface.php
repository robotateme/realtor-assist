<?php

declare(strict_types=1);

namespace Application\Port\Bus;

interface EventBusPortInterface
{
    public function dispatch(object $event): mixed;

    /**
     * @param iterable<object> $events
     */
    public function dispatchAll(iterable $events): void;
}
