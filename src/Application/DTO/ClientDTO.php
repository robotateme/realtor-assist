<?php

declare(strict_types=1);

namespace Application\DTO;

use Domain\Client\VO\ClientEmail;

class ClientDTO
{
    /**
     * @param string|null $fullName
     * @param ClientEmail|null $email
     * @param string|null $phone
     * @param null|int $userId
     * @param int|null $id
     */
    public function __construct(
        public ?string      $fullName,
        public ?ClientEmail $email,
        public ?string      $phone,
        public ?int        $userId = null,
        public ?int        $id = null,
    ) {
    }
}
