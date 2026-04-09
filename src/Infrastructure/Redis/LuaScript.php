<?php

declare(strict_types=1);

namespace Infrastructure\Redis;

interface LuaScript
{
    public function name(): string;

    public function body(): string;
}
