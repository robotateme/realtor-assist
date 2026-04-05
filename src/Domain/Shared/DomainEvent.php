<?php

declare(strict_types=1);

namespace Domain\Shared;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredOn(): DateTimeImmutable;
}
