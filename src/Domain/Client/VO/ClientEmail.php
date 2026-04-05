<?php

declare(strict_types=1);

namespace Domain\Client\VO;

use Exception;
use Override;
use Webmozart\Assert\Assert;
use Domain\Shared\DomainException;

final class ClientEmail
{
    private string $value;

    /**
     * @throws DomainException
     */
    public function __construct(
        string $value,
    ) {
        try {
            Assert::notEmpty($value);
            Assert::email($value);
            $this->value = $value;
        } catch (Exception $exception) {
            throw new DomainException($exception->getMessage(), previous: $exception);
        }

    }

    /**
     * @return string
     */
    #[Override]
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
