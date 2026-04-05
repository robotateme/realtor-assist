<?php

declare(strict_types=1);

namespace Domain\Client;

use Domain\Client\Event\ClientRegisteredDomainEvent;
use Domain\Client\VO\ClientEmail;
use Domain\Shared\AggregateRoot;

final class ClientEntity extends AggregateRoot
{
    public function __construct(
        private int $id,
        private string $fullName,
        private ClientEmail $email,
        private ?string $phone,
        private ?int $userId,
    ) {
    }

    public static function register(
        int $id,
        string $fullName,
        ClientEmail $email,
        ?string $phone,
        ?int $userId,
    ): self {
        $client = new self($id, $fullName, $email, $phone, $userId);
        $client->record(new ClientRegisteredDomainEvent(
            clientId: $id,
            fullName: $fullName,
            email: $email->getValue(),
            phone: $phone,
            userId: $userId,
        ));

        return $client;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email->getValue();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
