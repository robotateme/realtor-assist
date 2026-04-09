<?php

declare(strict_types=1);

namespace Infrastructure\Redis\Scripts;

use Infrastructure\Redis\LuaScript;
use RuntimeException;

final class RateLimitHitScript implements LuaScript
{
    public function name(): string
    {
        return 'rate-limit-hit';
    }

    public function body(): string
    {
        $path = __DIR__ . '/Lua/rate_limit_hit.lua';
        $script = file_get_contents($path);

        if ($script === false) {
            throw new RuntimeException(sprintf('Unable to load Lua script from "%s".', $path));
        }

        return $script;
    }
}
