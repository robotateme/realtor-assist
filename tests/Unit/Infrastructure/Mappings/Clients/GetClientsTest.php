<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Mappings\Clients;

use App\Models\Client;
use Infrastructure\Mappings\Clients\GetClients;
use PHPUnit\Framework\TestCase;

final class GetClientsTest extends TestCase
{
    public function test_it_maps_model_to_client_entity(): void
    {
        $model = new Client();
        $model->id = 42;
        $model->full_name = 'Jane Doe';
        $model->email = 'jane@example.com';
        $model->phone = '+10000000000';
        $model->user_id = 7;

        $entity = (new GetClients())->fromModel($model);

        self::assertSame(42, $entity->getId());
        self::assertSame('Jane Doe', $entity->getFullName());
        self::assertSame('jane@example.com', $entity->getEmail());
        self::assertSame('+10000000000', $entity->getPhone());
        self::assertSame(7, $entity->getUserId());
    }
}
