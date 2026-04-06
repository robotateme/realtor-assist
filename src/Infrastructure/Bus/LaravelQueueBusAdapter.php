<?php

declare(strict_types=1);

namespace Infrastructure\Bus;

use Application\Port\Bus\QueueBusPortInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Override;

final readonly class LaravelQueueBusAdapter implements QueueBusPortInterface
{
    public function __construct(
        private Dispatcher $dispatcher,
    ) {
    }

    #[Override]
    public function dispatch(object $command): mixed
    {
        return $this->dispatcher->dispatch($command);
    }
}
