<?php

namespace Application\DTO;

/**
 *
 */
class MessengerClientDTO
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public bool $isBot,
        public string $messengerId,
        public ?int $clientId = null,
    ) {
    }
}
