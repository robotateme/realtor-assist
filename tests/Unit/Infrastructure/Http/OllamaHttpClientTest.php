<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Http;

use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Response;
use Infrastructure\Http\OllamaHttpClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

final class OllamaHttpClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_sends_json_post_request_to_ollama_endpoint(): void
    {
        /** @var ClientInterface&MockInterface $client */
        $client = Mockery::mock(ClientInterface::class);
        $httpFactory = new HttpFactory();
        $response = new Response(200, [], '{"response":"ok"}');

        /** @var Expectation $expectation */
        $expectation = $client->shouldReceive('sendRequest');

        $expectation
            ->once()
            ->withArgs(static function (RequestInterface $request): bool {
                self::assertSame('POST', $request->getMethod());
                self::assertSame('http://127.0.0.1:11434/api/generate', (string) $request->getUri());
                self::assertSame('application/json', $request->getHeaderLine('Accept'));
                self::assertSame('application/json', $request->getHeaderLine('Content-Type'));
                self::assertSame('{"model":"llama3.2","prompt":"Hello"}', (string) $request->getBody());

                return true;
            })
            ->andReturn($response);

        $ollamaClient = new OllamaHttpClient(
            client: $client,
            requestFactory: $httpFactory,
            streamFactory: $httpFactory,
            baseUri: 'http://127.0.0.1:11434',
        );

        self::assertSame($response, $ollamaClient->postJson('/api/generate', [
            'model' => 'llama3.2',
            'prompt' => 'Hello',
        ]));
    }

    public function test_it_sends_chat_request_with_messages(): void
    {
        /** @var ClientInterface&MockInterface $client */
        $client = Mockery::mock(ClientInterface::class);
        $httpFactory = new HttpFactory();
        $response = new Response(200, [], '{"message":{"content":"ok"}}');

        /** @var Expectation $expectation */
        $expectation = $client->shouldReceive('sendRequest');

        $expectation
            ->once()
            ->withArgs(static function (RequestInterface $request): bool {
                self::assertSame('POST', $request->getMethod());
                self::assertSame('http://127.0.0.1:11434/api/chat', (string) $request->getUri());
                self::assertSame(
                    '{"stream":false,"model":"llama3.2","messages":[{"role":"system","content":"You are helpful."},{"role":"user","content":"Hello"}]}',
                    (string) $request->getBody(),
                );

                return true;
            })
            ->andReturn($response);

        $ollamaClient = new OllamaHttpClient(
            client: $client,
            requestFactory: $httpFactory,
            streamFactory: $httpFactory,
            baseUri: 'http://127.0.0.1:11434',
        );

        self::assertSame($response, $ollamaClient->chat('llama3.2', [
            ['role' => 'system', 'content' => 'You are helpful.'],
            ['role' => 'user', 'content' => 'Hello'],
        ], [
            'stream' => false,
        ]));
    }
}
