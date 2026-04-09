<?php

declare(strict_types=1);

namespace Application\Port\RateLimit;

use RuntimeException;

final class RateLimitExceededException extends RuntimeException
{
    public function __construct(
        public readonly string $bucket,
        public readonly int $retryAfterSeconds,
        public readonly int $resetAtUnix,
        public readonly int $limit,
        public readonly int $remaining,
    ) {
        parent::__construct(sprintf(
            'Rate limit exceeded for bucket "%s". Retry after %d seconds.',
            $bucket,
            $retryAfterSeconds,
        ));
    }
}
