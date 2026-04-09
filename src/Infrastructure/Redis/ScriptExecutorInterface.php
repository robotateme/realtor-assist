<?php

declare(strict_types=1);

namespace Infrastructure\Redis;

interface ScriptExecutorInterface
{
    /**
     * @param list<string> $keys
     * @param list<int|float|string> $arguments
     */
    public function execute(LuaScript $script, array $keys = [], array $arguments = []): mixed;
}
