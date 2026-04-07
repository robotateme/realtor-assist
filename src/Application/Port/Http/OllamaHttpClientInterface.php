<?php

declare(strict_types=1);

namespace Application\Port\Http;

use Psr\Http\Message\ResponseInterface;

interface OllamaHttpClientInterface
{
    /**
     * @param array<string, string> $headers
     * @param array<string, mixed> $payload
     */
    public function request(
        string $method,
        string $uri,
        array $headers = [],
        array $payload = [],
    ): ResponseInterface;

    /**
     * @param array<string, string> $headers
     * @param array<string, mixed> $payload
     */
    public function postJson(
        string $uri,
        array $payload = [],
        array $headers = [],
    ): ResponseInterface;

    /**
     * @param list<array{role:string,content:string}> $messages
     * @param array<string, mixed> $options
     */
    public function chat(
        string $model,
        array $messages,
        array $options = [],
    ): ResponseInterface;

}
