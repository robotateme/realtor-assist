<?php

declare(strict_types=1);

namespace Domain\MessengerClient;

use Domain\MessengerClient\VO\MessengerId;

final readonly class MessengerClient
{
    public function __construct(
        private MessengerProviderEnum $provider,
        private string $username,
        private string $firstName,
        private string $lastName,
        private string $id,
        private MessengerId $messengerId,
        private bool $isBot,
    ) {
    }

    public function getProvider(): MessengerProviderEnum
    {
        return $this->provider;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessengerId(): MessengerId
    {
        return $this->messengerId;
    }

    public function isBot(): bool
    {
        return $this->isBot;
    }
}
