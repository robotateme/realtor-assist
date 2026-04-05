<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use App\Models\DialogSession;
use Illuminate\Database\Seeder;

final class DialogSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::query()
            ->inRandomOrder()
            ->take(10)
            ->each(static function (Client $client): void {
                DialogSession::factory()
                    ->state(['client_id' => $client->getKey()])
                    ->create();
            });
    }
}
