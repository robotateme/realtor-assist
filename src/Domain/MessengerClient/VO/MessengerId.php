<?php

declare(strict_types=1);

namespace Domain\MessengerClient\VO;

use Domain\MessengerClient\MessengerProviderEnum;
use Domain\Shared\DomainException;
use Exception;
use Override;
use Webmozart\Assert\Assert;

final class MessengerId
{
    private string $value;

    /**
     * @throws DomainException
     */
    public function __construct(
        private readonly MessengerProviderEnum $provider,
        string $value,
    ) {
        try {
            Assert::notEmpty($value);

            if ($provider === MessengerProviderEnum::TELEGRAM) {
                Assert::regex($value, '/^\d+$/');
            }

            $this->value = $value;
        } catch (Exception $exception) {
            throw new DomainException($exception->getMessage(), previous: $exception);
        }
    }

    public function getProvider(): MessengerProviderEnum
    {
        return $this->provider;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    #[Override]
    public function __toString(): string
    {
        return $this->value;
    }
}
