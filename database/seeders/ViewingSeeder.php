<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Property;
use App\Models\Viewing;
use Illuminate\Database\Seeder;

final class ViewingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::query()->get();
        $properties = Property::query()->get();

        if ($clients->isEmpty() || $properties->isEmpty()) {
            return;
        }

        foreach ($clients as $client) {
            $count = random_int(1, 3);

            $properties
                ->shuffle()
                ->take($count)
                ->each(static function (Property $property) use ($client): void {
                    Viewing::factory()
                        ->state([
                            'client_id' => $client->getKey(),
                            'property_id' => $property->getKey(),
                        ])
                        ->create();
                });
        }
    }
}
