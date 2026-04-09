<?php

declare(strict_types=1);

namespace Tests\Feature;

use Application\Port\Cache\CacheStoreInterface;
use Override;
use Tests\TestCase;

final class CacheStoreBindingTest extends TestCase
{
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'cache.default' => 'array',
            'realtor-assist.cache.store' => 'array',
            'realtor-assist.cache.prefix' => 'test-suite',
            'realtor-assist.cache.default_ttl' => 60,
        ]);
    }

    public function test_it_resolves_cache_store_through_application_port(): void
    {
        /** @var CacheStoreInterface $cache */
        $cache = $this->app->make(CacheStoreInterface::class);

        self::assertNull($cache->get('clients:list'));

        $value = $cache->remember('clients:list', null, static fn (): array => ['first-load']);

        self::assertSame(['first-load'], $value);
        self::assertSame(['first-load'], $cache->get('clients:list'));

        $cache->put('clients:count', 3, 30);

        self::assertSame(3, $cache->get('clients:count'));
        self::assertTrue($cache->forget('clients:count'));
        self::assertNull($cache->get('clients:count'));
    }
}
