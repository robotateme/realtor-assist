<?php

declare(strict_types=1);

namespace Infrastructure\Http;

use Application\Port\Http\OllamaHttpClientInterface;
use JsonException;
use Override;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;

final readonly class OllamaHttpClient implements OllamaHttpClientInterface
{
    public function __construct(
        private ClientInterface $client,
        private RequestFactoryInterface $requestFactory,
        private StreamFactoryInterface $streamFactory,
        private string $baseUri,
        private ?string $apiKey = null,
    ) {
    }

    /**
     * @throws ClientExceptionInterface
     */
    #[Override]
    public function request(
        string $method,
        string $uri,
        array $headers = [],
        array $payload = [],
    ): ResponseInterface {
        $request = $this->requestFactory->createRequest($method, $this->resolveUri($uri));

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($this->apiKey !== null && $this->apiKey !== '') {
            $request = $request->withHeader('Authorization', 'Bearer ' . $this->apiKey);
        }

        if ($payload !== []) {
            $request = $request->withBody(
                $this->streamFactory->createStream($this->encodePayload($payload)),
            );
        }

        return $this->client->sendRequest($request);
    }

    /**
     * @throws ClientExceptionInterface
     */
    #[Override]
    public function postJson(
        string $uri,
        array $payload = [],
        array $headers = [],
    ): ResponseInterface {
        return $this->request(
            method: 'POST',
            uri: $uri,
            headers: array_merge([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ], $headers),
            payload: $payload,
        );
    }

    /**
     * @throws ClientExceptionInterface
     */
    #[Override]
    public function chat(
        string $model,
        array $messages,
        array $options = [],
    ): ResponseInterface {
        return $this->postJson('/api/chat', array_merge($options, [
            'model' => $model,
            'messages' => $messages,
        ]));
    }

    private function resolveUri(string $uri): string
    {
        return rtrim($this->baseUri, '/') . '/' . ltrim($uri, '/');
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function encodePayload(array $payload): string
    {
        try {
            return json_encode($payload, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Unable to encode Ollama request payload.', previous: $exception);
        }
    }
}
