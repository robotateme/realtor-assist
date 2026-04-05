<?php

declare(strict_types=1);

namespace Infrastructure\Mappings\Clients;

use App\Models\Client;
use Domain\Client\ClientEntity;
use Domain\Client\VO\ClientEmail;
use Domain\Shared\DomainException;

class GetClients
{
    /**
     * @throws DomainException
     */
    public function fromModel(Client $model): ClientEntity
    {
        return new ClientEntity(
            $model->id,
            $model->full_name,
            new ClientEmail($model->email),
            $model->phone,
            $model->user_id,
        );
    }
}
