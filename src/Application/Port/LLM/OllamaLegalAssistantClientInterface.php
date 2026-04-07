<?php

declare(strict_types=1);

namespace Application\Port\LLM;

interface OllamaLegalAssistantClientInterface
{
    /**
     * @param array<string, mixed> $currentJson
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function chatBase(
        string $model,
        array $currentJson,
        string $clientMessage,
        array $options = [],
    ): array;
}
