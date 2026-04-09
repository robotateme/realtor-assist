<?php

declare(strict_types=1);

namespace Infrastructure\RateLimit;

use Application\Port\RateLimit\RateLimiterInterface;
use Application\Port\RateLimit\RateLimitResult;
use Infrastructure\Redis\ScriptResolver;
use Infrastructure\Redis\Scripts\RateLimitHitScript;
use RuntimeException;

final readonly class RedisRateLimiter implements RateLimiterInterface
{
    public function __construct(
        private ScriptResolver $scriptResolver,
        private RateLimitHitScript $script,
        private string $prefix = 'rate-limit',
    ) {
    }

    public function hit(
        string $bucket,
        int $maxAttempts,
        int $windowSeconds,
        int $cost = 1,
    ): RateLimitResult {
        $nowMs = (int) floor(microtime(true) * 1000);

        $result = $this->scriptResolver->execute(
            script: $this->script,
            keys: [$this->resolveKey($bucket)],
            arguments: [$nowMs, $windowSeconds * 1000, $maxAttempts, $cost, uniqid('', true)],
        );

        if (! is_array($result) || count($result) !== 6) {
            throw new RuntimeException('Unexpected response from Redis rate limit script.');
        }

        return new RateLimitResult(
            allowed: (bool) $result[0],
            remaining: (int) $result[1],
            retryAfterSeconds: (int) $result[2],
            resetAtUnix: (int) $result[3],
            limit: (int) $result[4],
            current: (int) $result[5],
        );
    }

    private function resolveKey(string $bucket): string
    {
        return sprintf('%s:%s', trim($this->prefix, ':'), $bucket);
    }
}
