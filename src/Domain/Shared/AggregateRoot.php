<?php

declare(strict_types=1);

namespace Domain\Shared;

abstract class AggregateRoot
{
    /**
     * @var list<DomainEvent>
     */
    private array $recordedEvents = [];

    protected function record(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    /**
     * @return list<DomainEvent>
     */
    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];

        return $events;
    }

    /**
     * @return list<DomainEvent>
     */
    public function domainEvents(): array
    {
        return $this->recordedEvents;
    }

    public function hasDomainEvents(): bool
    {
        return $this->recordedEvents !== [];
    }
}
