<?php

declare(strict_types=1);

namespace Application\Port\RateLimit;

interface RateLimiterInterface
{
    public function hit(
        string $bucket,
        int $maxAttempts,
        int $windowSeconds,
        int $cost = 1,
    ): RateLimitResult;
}
