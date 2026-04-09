<?php

declare(strict_types=1);

namespace Infrastructure\Http;

use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\RateLimit\RateLimiterInterface;
use Application\Port\RateLimit\RateLimitExceededException;
use Override;
use Psr\Http\Message\ResponseInterface;

final readonly class RateLimitedOllamaHttpClient implements OllamaHttpClientInterface
{
    /**
     * @param array{enabled:bool,bucket:string,max_attempts:int,window_seconds:int,cost:int} $config
     */
    public function __construct(
        private OllamaHttpClientInterface $inner,
        private RateLimiterInterface $rateLimiter,
        private array $config,
    ) {
    }

    #[Override]
    public function request(
        string $method,
        string $uri,
        array $headers = [],
        array $payload = [],
    ): ResponseInterface {
        $this->guard($method, $uri);

        return $this->inner->request($method, $uri, $headers, $payload);
    }

    #[Override]
    public function postJson(
        string $uri,
        array $payload = [],
        array $headers = [],
    ): ResponseInterface {
        $this->guard('POST', $uri);

        return $this->inner->postJson($uri, $payload, $headers);
    }

    #[Override]
    public function chat(
        string $model,
        array $messages,
        array $options = [],
    ): ResponseInterface {
        if (! $this->config['enabled']) {
            return $this->inner->chat($model, $messages, $options);
        }

        $bucket = sprintf('%s:%s', $this->config['bucket'], $model);
        $decision = $this->rateLimiter->hit(
            bucket: $bucket,
            maxAttempts: $this->config['max_attempts'],
            windowSeconds: $this->config['window_seconds'],
            cost: $this->config['cost'],
        );

        if (! $decision->allowed) {
            throw new RateLimitExceededException(
                bucket: $bucket,
                retryAfterSeconds: $decision->retryAfterSeconds,
                resetAtUnix: $decision->resetAtUnix,
                limit: $decision->limit,
                remaining: $decision->remaining,
            );
        }

        return $this->inner->chat($model, $messages, $options);
    }

    private function guard(string $method, string $uri): void
    {
        if (! $this->config['enabled']) {
            return;
        }

        if ($method !== 'POST' || ! str_ends_with($uri, '/api/chat')) {
            return;
        }

        $decision = $this->rateLimiter->hit(
            bucket: $this->config['bucket'],
            maxAttempts: $this->config['max_attempts'],
            windowSeconds: $this->config['window_seconds'],
            cost: $this->config['cost'],
        );

        if (! $decision->allowed) {
            throw new RateLimitExceededException(
                bucket: $this->config['bucket'],
                retryAfterSeconds: $decision->retryAfterSeconds,
                resetAtUnix: $decision->resetAtUnix,
                limit: $decision->limit,
                remaining: $decision->remaining,
            );
        }
    }
}
