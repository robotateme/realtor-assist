<?php

declare(strict_types=1);

namespace Application\Command;

final class ClientRegisterCommand
{
    /**
     * @param int $clientId
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param string $location
     */
    public function __construct(
        public int $clientId,
        public string $name,
        public string $email,
        public string $phone,
        public string $location,
    ) {
    }
}
