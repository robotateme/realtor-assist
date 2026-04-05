<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\DialogSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<DialogSession>
 */
final class DialogSessionFactory extends Factory
{
    /** @var class-string<DialogSession> */
    protected $model = DialogSession::class;

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
            'current_intent' => fake()->numberBetween(0, 5),
            'context_data' => [
                'source' => fake()->randomElement(['telegram', 'site', 'whatsapp']),
                'step' => fake()->randomElement(['greeting', 'qualification', 'selection']),
                'budget' => fake()->randomElement(['<100k', '100k-300k', '300k-700k', '700k+']),
            ],
            'last_message' => fake()->optional()->sentence(),
        ];
    }
}
