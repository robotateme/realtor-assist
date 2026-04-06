<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Application\DTO\ClientDTO;
use Application\Query\Clients\Repositories\ClientsReadRepositoryInterface;
use Application\Query\Clients\Repositories\ClientsWriteRepositoryInterface;
use Domain\Client\VO\ClientEmail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use JetBrains\PhpStorm\NoReturn;

#[Signature('app:testing')]
#[Description('Command description')]
class Testing extends Command
{
    /**
     * Execute the console command.
     */
    #[NoReturn]
    public function handle(
        ClientsWriteRepositoryInterface $clientsRepository,
        ClientsReadRepositoryInterface  $clientsReadRepository,
    ): void {
        $clientEntity = $clientsReadRepository->findById(19);
        $dto = new ClientDTO(
            $clientEntity->getFullName(),
            new ClientEmail($clientEntity->getEmail()),
            '666-7-7',
            $clientEntity->getUserId(),
            $clientEntity->getId(),
        );

        $result = $clientsRepository->updateOne($dto);
        dd($result);
    }
}
