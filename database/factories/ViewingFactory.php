<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Property;
use App\Models\Viewing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Viewing>
 */
final class ViewingFactory extends Factory
{
    /** @var class-string<Viewing> */
    protected $model = Viewing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'property_id' => Property::factory(),
            'scheduled_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'status' => fake()->numberBetween(0, 2),
        ];
    }
}
