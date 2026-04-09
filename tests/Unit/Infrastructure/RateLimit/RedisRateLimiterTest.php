<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\RateLimit;

use Infrastructure\RateLimit\RedisRateLimiter;
use Infrastructure\Redis\ScriptResolver;
use Infrastructure\Redis\Scripts\RateLimitHitScript;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class RedisRateLimiterTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_maps_lua_response_to_result_object(): void
    {
        /** @var ScriptResolver&MockInterface $resolver */
        $resolver = Mockery::mock(ScriptResolver::class);
        $script = new RateLimitHitScript();

        /** @var Expectation $expectation */
        $expectation = $resolver->shouldReceive('execute');
        $expectation
            ->once()
            ->withArgs(static function (RateLimitHitScript $actualScript, array $keys, array $arguments) use ($script): bool {
                self::assertSame($script->name(), $actualScript->name());
                self::assertSame(['rl:ollama:chat'], $keys);
                self::assertIsInt($arguments[0]);
                self::assertSame(60000, $arguments[1]);
                self::assertSame(30, $arguments[2]);
                self::assertSame(1, $arguments[3]);
                self::assertIsString($arguments[4]);

                return true;
            })
            ->andReturn([1, 29, 0, 1710000060, 30, 1]);

        $limiter = new RedisRateLimiter($resolver, $script, 'rl');

        $result = $limiter->hit('ollama:chat', 30, 60, 1);

        self::assertTrue($result->allowed);
        self::assertSame(29, $result->remaining);
        self::assertSame(0, $result->retryAfterSeconds);
        self::assertSame(1710000060, $result->resetAtUnix);
    }
}
