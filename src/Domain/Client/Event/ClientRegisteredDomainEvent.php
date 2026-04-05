<?php

declare(strict_types=1);

namespace Domain\Client\Event;

use DateTimeImmutable;
use Domain\Shared\DomainEvent;
use Override;

final readonly class ClientRegisteredDomainEvent implements DomainEvent
{
    public function __construct(
        public int $clientId,
        public string $fullName,
        public string $email,
        public ?string $phone,
        public ?int $userId,
        private DateTimeImmutable $occurredOn = new DateTimeImmutable(),
    ) {
    }

    #[Override]
    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
