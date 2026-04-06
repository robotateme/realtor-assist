<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use App\Models\Client;
use Application\DTO\ClientDTO;
use Application\Query\Clients\Repositories\ClientsWriteRepositoryInterface;
use Domain\Client\ClientEntity;
use Domain\Shared\DomainException;
use Illuminate\Database\UniqueConstraintViolationException;
use Infrastructure\Mappings\Clients\GetClients;
use Override;

/**
 *
 */
final readonly class ClientsWriteRepository implements ClientsWriteRepositoryInterface
{
    /**
     * @throws DomainException
     */
    #[Override]
    public function create(ClientDTO $clientDTO): ClientEntity
    {
        try {
            $client = Client::query()->create([
                'user_id' => $clientDTO->userId,
                'full_name' => $clientDTO->fullName,
                'email' => $clientDTO->email->getValue(),
                'phone' => $clientDTO->phone,
            ]);
        } catch (UniqueConstraintViolationException $exception) {
            throw new DomainException($exception->getMessage(), previous: $exception);
        }

        return new GetClients()->fromModel($client);
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
