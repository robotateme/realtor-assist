<?php

declare(strict_types=1);

namespace Infrastructure\Cache;

use Application\Port\Cache\CacheStoreInterface;
use Closure;
use DateInterval;
use DateTimeInterface;
use Illuminate\Contracts\Cache\Repository;
use Override;

final readonly class LaravelCacheStore implements CacheStoreInterface
{
    public function __construct(
        private Repository $repository,
        private string $prefix = '',
        private ?int $defaultTtl = null,
    ) {
    }

    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->repository->get($this->key($key), $default);
    }

    #[Override]
    public function put(string $key, mixed $value, DateInterval|DateTimeInterface|int|null $ttl = null): void
    {
        $resolvedTtl = $this->resolveTtl($ttl);

        if ($resolvedTtl === null) {
            $this->repository->forever($this->key($key), $value);

            return;
        }

        $this->repository->put($this->key($key), $value, $resolvedTtl);
    }

    #[Override]
    public function remember(string $key, DateInterval|DateTimeInterface|int|null $ttl, callable $resolver): mixed
    {
        $cacheKey = $this->key($key);
        $resolvedTtl = $this->resolveTtl($ttl);
        $callback = Closure::fromCallable($resolver);

        if ($resolvedTtl !== null) {
            return $this->repository->remember($cacheKey, $resolvedTtl, $callback);
        }

        if ($this->repository->has($cacheKey)) {
            return $this->repository->get($cacheKey);
        }

        $value = $callback();
        $this->repository->forever($cacheKey, $value);

        return $value;
    }

    #[Override]
    public function forget(string $key): bool
    {
        return $this->repository->forget($this->key($key));
    }

    private function key(string $key): string
    {
        if ($this->prefix === '') {
            return $key;
        }

        return sprintf('%s:%s', trim($this->prefix, ':'), $key);
    }

    private function resolveTtl(DateInterval|DateTimeInterface|int|null $ttl): DateInterval|DateTimeInterface|int|null
    {
        return $ttl ?? $this->defaultTtl;
    }
}
