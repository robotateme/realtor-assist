<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use Application\Port\Bus\EventBusPortInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Override;

final readonly class LaravelEventBusAdapter implements EventBusPortInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
    ) {
    }

    #[Override]
    public function dispatch(object $event): mixed
    {
        return $this->dispatcher->dispatch($event);
    }

    #[Override]
    public function dispatchAll(iterable $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }
}
