<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use Domain\Property\TypesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Property>
 */
final class PropertyFactory extends Factory
{
    /** @var class-string<Property> */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement([
                'Cozy Downtown Apartment',
                'Modern Family House',
                'Spacious Office Loft',
                'Retail Corner Space',
                'Investment Land Parcel',
            ]),
            'location' => fake()->city(),
            'price' => fake()->numberBetween(50_000, 5_000_000),
            'type' => fake()->randomElement(TypesEnum::cases()),
            'availability_status' => fake()->numberBetween(0, 2),
        ];
    }
}
