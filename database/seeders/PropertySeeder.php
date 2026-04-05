<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Property;
use Domain\Property\TypesEnum;
use Illuminate\Database\Seeder;

final class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Property::factory()->count(20)->create();

        Property::factory()->create([
            'title' => 'Flagship Downtown Apartment',
            'location' => 'Los Angeles',
            'price' => 850_000,
            'type' => TypesEnum::APARTMENT,
            'availability_status' => 1,
        ]);

        Property::factory()->create([
            'title' => 'Commercial Retail Anchor',
            'location' => 'San Diego',
            'price' => 1_450_000,
            'type' => TypesEnum::RETAIL,
            'availability_status' => 0,
        ]);
    }
}
