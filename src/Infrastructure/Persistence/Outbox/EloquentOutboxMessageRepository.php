<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Outbox;

use App\Models\OutboxMessage as OutboxMessageModel;
use Application\DTO\OutboxMessage;
use Application\Port\Outbox\EventSerializerInterface;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use DateTimeImmutable;
use Domain\Shared\DomainEvent;
use Override;

final readonly class EloquentOutboxMessageRepository implements OutboxMessageRepositoryInterface
{
    public function __construct(
        private EventSerializerInterface $serializer,
    ) {
    }

    #[Override]
    public function add(object $event): void
    {
        OutboxMessageModel::query()->create([
            'event_class' => $event::class,
            'payload' => $this->serializer->serialize($event),
            'occurred_on' => $this->resolveOccurredOn($event),
        ]);
    }

    #[Override]
    public function addAll(iterable $events): void
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    #[Override]
    public function pending(int $limit = 100): array
    {
        return OutboxMessageModel::query()
            ->whereNull('published_at')
            ->orderBy('occurred_on')
            ->limit($limit)
            ->get()
            ->map(function (OutboxMessageModel $message): OutboxMessage {
                /** @var array<string, mixed> $payload */
                $payload = $message->payload;

                return new OutboxMessage(
                    id: $message->id,
                    eventClass: $message->event_class,
                    event: $this->serializer->deserialize($message->event_class, $payload),
                    occurredOn: $message->occurred_on->toDateTimeImmutable(),
                    publishedAt: $message->published_at?->toDateTimeImmutable(),
                );
            })
            ->all();
    }

    #[Override]
    public function markAsPublished(string $messageId, ?DateTimeImmutable $publishedAt = null): void
    {
        OutboxMessageModel::query()
            ->whereKey($messageId)
            ->update([
                'published_at' => $publishedAt ?? new DateTimeImmutable(),
            ]);
    }

    private function resolveOccurredOn(object $event): DateTimeImmutable
    {
        if ($event instanceof DomainEvent) {
            return $event->occurredOn();
        }

        return new DateTimeImmutable();
    }
}
