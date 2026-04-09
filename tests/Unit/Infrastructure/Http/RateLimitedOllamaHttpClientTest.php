<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http;

use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\RateLimit\RateLimiterInterface;
use Application\Port\RateLimit\RateLimitExceededException;
use Application\Port\RateLimit\RateLimitResult;
use Infrastructure\Http\RateLimitedOllamaHttpClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class RateLimitedOllamaHttpClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_allows_chat_request_when_rate_limit_allows(): void
    {
        /** @var OllamaHttpClientInterface&MockInterface $inner */
        $inner = Mockery::mock(OllamaHttpClientInterface::class);
        /** @var RateLimiterInterface&MockInterface $limiter */
        $limiter = Mockery::mock(RateLimiterInterface::class);
        /** @var ResponseInterface&MockInterface $response */
        $response = Mockery::mock(ResponseInterface::class);

        /** @var Expectation $limitExpectation */
        $limitExpectation = $limiter->shouldReceive('hit');
        $limitExpectation
            ->once()
            ->with('ollama:chat:qwen', 30, 60, 1)
            ->andReturn(new RateLimitResult(true, 29, 0, 1710000060, 30, 1));

        /** @var Expectation $chatExpectation */
        $chatExpectation = $inner->shouldReceive('chat');
        $chatExpectation
            ->once()
            ->with('qwen', [['role' => 'user', 'content' => 'Hello']], ['stream' => false])
            ->andReturn($response);

        $client = new RateLimitedOllamaHttpClient($inner, $limiter, [
            'enabled' => true,
            'bucket' => 'ollama:chat',
            'max_attempts' => 30,
            'window_seconds' => 60,
            'cost' => 1,
        ]);

        self::assertSame($response, $client->chat('qwen', [['role' => 'user', 'content' => 'Hello']], ['stream' => false]));
    }

    public function test_it_rejects_chat_request_when_rate_limit_is_exceeded(): void
    {
        /** @var OllamaHttpClientInterface&MockInterface $inner */
        $inner = Mockery::mock(OllamaHttpClientInterface::class);
        /** @var RateLimiterInterface&MockInterface $limiter */
        $limiter = Mockery::mock(RateLimiterInterface::class);

        /** @var Expectation $limitExpectation */
        $limitExpectation = $limiter->shouldReceive('hit');
        $limitExpectation
            ->once()
            ->with('ollama:chat:qwen', 30, 60, 1)
            ->andReturn(new RateLimitResult(false, 0, 12, 1710000060, 30, 30));

        $client = new RateLimitedOllamaHttpClient($inner, $limiter, [
            'enabled' => true,
            'bucket' => 'ollama:chat',
            'max_attempts' => 30,
            'window_seconds' => 60,
            'cost' => 1,
        ]);

        $this->expectException(RateLimitExceededException::class);

        $client->chat('qwen', [['role' => 'user', 'content' => 'Hello']]);
    }
}
