<?php

declare(strict_types=1);

namespace Application\DTO;

use Domain\Client\VO\ClientEmail;

class ClientDTO
{
    public function __construct(
        public string       $fullName,
        public ClientEmail  $email,
        public ?string      $phone,
        public ?int         $userId = null,
        public ?int         $id = null,
    ) {
    }
}
