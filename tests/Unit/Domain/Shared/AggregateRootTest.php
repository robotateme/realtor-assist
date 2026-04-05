<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Shared;

use DateTimeImmutable;
use Domain\Shared\AggregateRoot;
use Domain\Shared\DomainEvent;
use Override;
use PHPUnit\Framework\TestCase;

final class AggregateRootTest extends TestCase
{
    public function test_it_tracks_and_releases_recorded_events(): void
    {
        $aggregate = new TestAggregateRoot();

        self::assertFalse($aggregate->hasDomainEvents());

        $aggregate->recordTestEvent(new TestDomainEvent());

        self::assertTrue($aggregate->hasDomainEvents());
        self::assertCount(1, $aggregate->domainEvents());
        self::assertCount(1, $aggregate->releaseEvents());
        self::assertFalse($aggregate->hasDomainEvents());
    }
}

final class TestAggregateRoot extends AggregateRoot
{
    public function recordTestEvent(DomainEvent $event): void
    {
        $this->record($event);
    }
}

final readonly class TestDomainEvent implements DomainEvent
{
    #[Override]
    public function occurredOn(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
