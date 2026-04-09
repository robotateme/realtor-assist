<?php

declare(strict_types=1);

namespace Application\Port\Cache;

use DateInterval;
use DateTimeInterface;

interface CacheStoreInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function put(string $key, mixed $value, DateInterval|DateTimeInterface|int|null $ttl = null): void;

    /**
     * @template T
     *
     * @param callable(): T $resolver
     *
     * @return T
     */
    public function remember(string $key, DateInterval|DateTimeInterface|int|null $ttl, callable $resolver): mixed;

    public function forget(string $key): bool;
}
