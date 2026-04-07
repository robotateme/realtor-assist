<?php

declare(strict_types=1);

namespace Infrastructure\LLM;

use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\LLM\OllamaLegalAssistantClientInterface;
use Application\Port\Prompt\PromptRendererInterface;
use JsonException;
use Override;
use RuntimeException;

final readonly class OllamaLegalAssistantClient implements OllamaLegalAssistantClientInterface
{
    public function __construct(
        private OllamaHttpClientInterface $httpClient,
        private PromptRendererInterface $promptRenderer,
    ) {
    }

    #[Override]
    public function chatBase(
        string $model,
        array $currentJson,
        string $clientMessage,
        array $options = [],
    ): array {
        $response = $this->httpClient->chat(
            model: $model,
            messages: [
                [
                    'role' => 'system',
                    'content' => $this->promptRenderer->resolveView('components.ai_prompts.ollama.base.system'),
                ],
                [
                    'role' => 'user',
                    'content' => $this->promptRenderer->resolveView('components.ai_prompts.ollama.base.user', [
                        'currentJson' => $this->encodeCurrentJson($currentJson),
                        'clientMessage' => $clientMessage,
                    ]),
                ],
            ],
            options: $options,
        );

        return $this->decodeResponse((string) $response->getBody());
    }

    /**
     * @param array<string, mixed> $currentJson
     */
    private function encodeCurrentJson(array $currentJson): string
    {
        try {
            return json_encode($currentJson, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (JsonException $exception) {
            throw new RuntimeException('Unable to encode current legal assistant state.', previous: $exception);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeResponse(string $response): array
    {
        try {
            /** @var mixed $decoded */
            $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Unable to decode Ollama response.', previous: $exception);
        }

        if (! is_array($decoded)) {
            throw new RuntimeException('Ollama response must decode to an array.');
        }

        return $decoded;
    }
}
