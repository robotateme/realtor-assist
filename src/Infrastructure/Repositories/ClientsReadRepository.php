<?php

declare(strict_types=1);

namespace Infrastructure\Repositories;

use App\Models\Client;
use Application\Criteria\Persistence\Criteria;
use Domain\Client\ClientEntity;
use Domain\Shared\DomainException;
use Infrastructure\Mappings\Clients\GetClients;
use Infrastructure\Persistence\Repositories\ClientsReadRepositoryInterface;
use Infrastructure\Persistence\Repositories\EloquentFilterContext;
use Override;

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
        /** @var \Illuminate\Database\Eloquent\Builder<Client> $builder */
        $builder = $this->filterContext->apply(Client::query(), $criteria);

        /** @var \Illuminate\Database\Eloquent\Collection<int, Client> $clients */
        $clients = $builder->get();

        return $clients
            ->map(fn (Client $client): ClientEntity => $this->mapper->fromModel($client))
            ->all();
    }

    /**
     * @param int|string $id
     * @return ClientEntity|null
     * @throws DomainException
     */
    #[Override]
    public function findById(int|string $id): ?ClientEntity
    {
        /** @var Client|null $client */
        $client = Client::query()->find($id);

        return $client instanceof Client ? $this->mapper->fromModel($client) : null;
    }

    /**
     * @param Criteria $criteria
     * @return ClientEntity|null
     * @throws DomainException
     */
    #[Override]
    public function findOneBy(Criteria $criteria): ?ClientEntity
    {
        /** @var \Illuminate\Database\Eloquent\Builder<Client> $builder */
        $builder = $this->filterContext->apply(Client::query(), $criteria);

        /** @var Client|null $client */
        $client = $builder->first();

        return $client instanceof Client ? $this->mapper->fromModel($client) : null;
    }
}
