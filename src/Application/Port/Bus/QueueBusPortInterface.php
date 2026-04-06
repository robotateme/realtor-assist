<?php

declare(strict_types=1);

namespace Application\Port\Bus;

interface QueueBusPortInterface
{
    public function dispatch(object $command): mixed;
}
