<?php

declare(strict_types=1);

namespace Application\Port\RateLimit;

final readonly class RateLimitResult
{
    public function __construct(
        public bool $allowed,
        public int $remaining,
        public int $retryAfterSeconds,
        public int $resetAtUnix,
        public int $limit,
        public int $current,
    ) {
    }
}
