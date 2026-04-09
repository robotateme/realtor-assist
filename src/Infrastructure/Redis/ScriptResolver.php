<?php

declare(strict_types=1);

namespace Infrastructure\Redis;

use Illuminate\Contracts\Redis\Factory;
use Illuminate\Redis\Connections\Connection;
use Throwable;

final class ScriptResolver implements ScriptExecutorInterface
{
    public function __construct(
        private readonly Factory $redis,
        private readonly string $connection = 'default',
    ) {
    }

    /**
     * @param list<string> $keys
     * @param list<int|float|string> $arguments
     */
    public function execute(LuaScript $script, array $keys = [], array $arguments = []): mixed
    {
        $connection = $this->connection();
        $scriptBody = $script->body();
        $flatArguments = [...$keys, ...$arguments];

        try {
            return $connection->evalsha($scriptBody, count($keys), ...$flatArguments);
        } catch (Throwable $exception) {
            if (! $this->isMissingScriptException($exception)) {
                throw $exception;
            }
        }

        return $connection->eval($scriptBody, count($keys), ...$flatArguments);
    }

    private function connection(): Connection
    {
        /** @var Connection $connection */
        $connection = $this->redis->connection($this->connection);

        return $connection;
    }

    private function isMissingScriptException(Throwable $exception): bool
    {
        return str_contains(strtoupper($exception->getMessage()), 'NOSCRIPT');
    }
}
