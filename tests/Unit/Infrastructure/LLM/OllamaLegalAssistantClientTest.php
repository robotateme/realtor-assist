<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\LLM;

use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\Prompt\PromptRendererInterface;
use GuzzleHttp\Psr7\Response;
use Infrastructure\LLM\OllamaLegalAssistantClient;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class OllamaLegalAssistantClientTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_renders_base_prompts_and_decodes_ollama_response(): void
    {
        /** @var OllamaHttpClientInterface&MockInterface $httpClient */
        $httpClient = Mockery::mock(OllamaHttpClientInterface::class);
        /** @var PromptRendererInterface&MockInterface $promptRenderer */
        $promptRenderer = Mockery::mock(PromptRendererInterface::class);

        /** @var Expectation $systemExpectation */
        $systemExpectation = $promptRenderer->shouldReceive('resolveView');
        $systemExpectation
            ->once()
            ->with('components.ai_prompts.ollama.base.system')
            ->andReturn('system prompt');

        /** @var Expectation $userExpectation */
        $userExpectation = $promptRenderer->shouldReceive('resolveView');
        $userExpectation
            ->once()
            ->with('components.ai_prompts.ollama.base.user', [
                'currentJson' => '{"facts":[]}',
                'clientMessage' => 'Здравствуйте',
            ])
            ->andReturn('user prompt');

        /** @var Expectation $chatExpectation */
        $chatExpectation = $httpClient->shouldReceive('chat');
        $chatExpectation
            ->once()
            ->with(
                'llama3.2',
                [
                    ['role' => 'system', 'content' => 'system prompt'],
                    ['role' => 'user', 'content' => 'user prompt'],
                ],
                ['stream' => false],
            )
            ->andReturn(new Response(200, [], '{"json_update":{"facts":["уволен вчера"]},"stage":"collect_facts"}'));

        $client = new OllamaLegalAssistantClient($httpClient, $promptRenderer);

        self::assertSame([
            'json_update' => [
                'facts' => ['уволен вчера'],
            ],
            'stage' => 'collect_facts',
        ], $client->chatBase(
            model: 'llama3.2',
            currentJson: ['facts' => []],
            clientMessage: 'Здравствуйте',
            options: ['stream' => false],
        ));
    }
}
