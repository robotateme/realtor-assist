<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

final class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! User::query()->exists()) {
            User::factory()->count(3)->create();
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, User> $users */
        $users = User::query()->get();

        $users->each(static function (User $user): void {
            Client::factory()
                ->count(4)
                ->state(['user_id' => $user->getKey()])
                ->create();
        });
    }
}
