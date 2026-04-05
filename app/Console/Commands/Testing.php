<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Infrastructure\Persistence\Repositories\ClientsReadRepositoryInterface;

#[Signature('app:testing')]
#[Description('Command description')]
class Testing extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ClientsReadRepositoryInterface $clientsReadRepository): void
    {
        dd($clientsReadRepository->findById(19));
    }
}
