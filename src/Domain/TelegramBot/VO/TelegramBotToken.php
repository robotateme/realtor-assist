<?php

declare(strict_types=1);

namespace Domain\TelegramBot\VO;

use Domain\Shared\DomainException;
use Exception;
use Override;
use Webmozart\Assert\Assert;

final class TelegramBotToken
{
    private string $value;

    /**
     * @throws DomainException
     */
    public function __construct(string $value)
    {
        try {
            Assert::notEmpty($value);
            Assert::regex($value, '/^\d+:[A-Za-z0-9_-]{20,}$/');

            $this->value = $value;
        } catch (Exception $exception) {
            throw new DomainException($exception->getMessage(), previous: $exception);
        }
    }

    /**
     * @throws DomainException
     */
    public function botId(): TelegramBotId
    {
        [$botId] = explode(':', $this->value, 2);

        return new TelegramBotId($botId);
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
