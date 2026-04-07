<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use App\Models\Client;
use Application\Command\Repositories\DB\ClientsWriteRepositoryInterface;
use Application\DTO\ClientDTO;
use Application\Port\Persistence\OutboxMessageRepositoryInterface;
use Domain\Client\ClientEntity;
use Domain\Client\VO\ClientEmail;
use Domain\Shared\DomainException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Override;

/**
 *
 */
final readonly class ClientsWriteRepository implements ClientsWriteRepositoryInterface
{
    public function __construct(
        private ?OutboxMessageRepositoryInterface $outboxMessages = null,
    ) {
    }

    /**
     * @throws DomainException
     */
    #[Override]
    public function create(ClientDTO $clientDTO): ClientEntity
    {
        try {
            return DB::transaction(function () use ($clientDTO): ClientEntity {
                $client = Client::query()->create([
                    'user_id' => $clientDTO->userId,
                    'full_name' => $clientDTO->fullName,
                    'email' => $clientDTO->email->getValue(),
                    'phone' => $clientDTO->phone,
                ]);

                $entity = ClientEntity::register(
                    id: $client->id,
                    fullName: $client->full_name,
                    email: new ClientEmail($client->email),
                    phone: $client->phone,
                    userId: $client->user_id,
                );
                $this->outboxMessages?->addAll($entity->domainEvents());

                return $entity;
            });
        } catch (UniqueConstraintViolationException $exception) {
            throw new DomainException($exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @param ClientDTO $clientDTO
     * @return bool
     * @throws DomainException
     */
    #[Override]
    public function updateOne(ClientDTO $clientDTO): bool
    {
        if ($clientDTO->id === null) {
            throw new DomainException('Client id is required for update');
        }

        try {
            return Client::query()
                    ->whereKey($clientDTO->id)
                    ->update([
                        'full_name' => $clientDTO->fullName,
                        'email' => $clientDTO->email->getValue(),
                        'phone' => $clientDTO->phone,
                        'user_id' => $clientDTO->userId,
                    ]) === 1;
        } catch (UniqueConstraintViolationException $exception) {
            throw new DomainException($exception->getMessage(), previous: $exception);
        }
    }
}
