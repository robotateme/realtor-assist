<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use App\Models\Client;
use Application\Criteria\Persistence\Criteria;
use Application\Query\Clients\Repositories\ClientsReadRepositoryInterface;
use Domain\Client\ClientEntity;
use Domain\Shared\DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Override;

/**
 *
 */
final readonly class ClientsReadRepository implements ClientsReadRepositoryInterface
{
    public function __construct(
        private EloquentFilterContext $filterContext,
        private GetClients            $mapper,
    ) {
    }

    /**
     * @return list<ClientEntity>
     */
    #[Override]
    public function all(Criteria $criteria): iterable
    {
        /** @var Builder<Client> $builder */
        $builder = $this->filterContext->apply(Client::query(), $criteria);

        /** @var Collection<int, Client> $clients */
        $clients = $builder->get();

        return $clients
            ->map(fn (Client $client): ClientEntity => $this->mapper->fromModel($client))
            ->all();
    }

    /**
     * @param int|string $id
     * @throws DomainException
     */
    #[Override]
    public function findById(int|string $id): ClientEntity
    {
        /** @var Client|null $client */
        $client = Client::query()->find($id);

        if (is_null($client)) {
            throw new DomainException('Client not found');
        }

        return $this->mapper->fromModel($client);
    }

    /**
     * @param Criteria $criteria
     * @throws DomainException
     */
    #[Override]
    public function findOneBy(Criteria $criteria): ClientEntity
    {
        /** @var Builder<Client> $builder */
        $builder = $this->filterContext->apply(Client::query(), $criteria);

        /** @var Client|null $client */
        $client = $builder->first();
        if (is_null($client)) {
            throw new DomainException('Client not found');
        }

        return $this->mapper->fromModel($client);
    }
}
