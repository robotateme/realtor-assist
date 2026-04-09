<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\LLM;

use Application\Port\Http\OllamaHttpClientInterface;
use Application\Port\Prompt\PromptResolverInterface;
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
        /** @var PromptResolverInterface&MockInterface $promptResolver */
        $promptResolver = Mockery::mock(PromptResolverInterface::class);

        /** @var Expectation $messagesExpectation */
        $messagesExpectation = $promptResolver->shouldReceive('resolveMessages');
        $messagesExpectation
            ->once()
            ->with([
                [
                    'role' => 'system',
                    'views' => ['components.ai_prompts.ollama.base.system'],
                ],
                [
                    'role' => 'user',
                    'views' => ['components.ai_prompts.ollama.base.user'],
                ],
            ], [
                'currentJson' => '{"facts":[]}',
                'clientMessage' => 'Здравствуйте',
            ])
            ->andReturn([
                ['role' => 'system', 'content' => 'system prompt'],
                ['role' => 'user', 'content' => 'user prompt'],
            ]);

        /** @var Expectation $chatExpectation */
        $chatExpectation = $httpClient->shouldReceive('chat');
        $chatExpectation
            ->once()
            ->with(
                'qwen3:32b',
                [
                    ['role' => 'system', 'content' => 'system prompt'],
                    ['role' => 'user', 'content' => 'user prompt'],
                ],
                ['stream' => false],
            )
            ->andReturn(new Response(200, [], '{"json_update":{"facts":["уволен вчера"]},"stage":"collect_facts"}'));

        $client = new OllamaLegalAssistantClient(
            $httpClient,
            $promptResolver,
            ['qwen' => 'qwen3:32b', 'gpt' => 'gpt-oss:120b'],
            'qwen',
        );

        self::assertSame([
            'json_update' => [
                'facts' => ['уволен вчера'],
            ],
            'stage' => 'collect_facts',
        ], $client->chatBase(
            model: 'qwen',
            currentJson: ['facts' => []],
            clientMessage: 'Здравствуйте',
            options: ['stream' => false],
        ));
    }

    public function test_it_accepts_raw_model_name_without_alias_resolution(): void
    {
        /** @var OllamaHttpClientInterface&MockInterface $httpClient */
        $httpClient = Mockery::mock(OllamaHttpClientInterface::class);
        /** @var PromptResolverInterface&MockInterface $promptResolver */
        $promptResolver = Mockery::mock(PromptResolverInterface::class);

        /** @var Expectation $messagesExpectation */
        $messagesExpectation = $promptResolver->shouldReceive('resolveMessages');
        $messagesExpectation
            ->once()
            ->andReturn([
                ['role' => 'system', 'content' => 'system prompt'],
                ['role' => 'user', 'content' => 'user prompt'],
            ]);

        /** @var Expectation $chatExpectation */
        $chatExpectation = $httpClient
            ->shouldReceive('chat');

        $chatExpectation
            ->once()
            ->with(
                'custom-model',
                [
                    ['role' => 'system', 'content' => 'system prompt'],
                    ['role' => 'user', 'content' => 'user prompt'],
                ],
                [],
            )
            ->andReturn(new Response(200, [], '{"stage":"collect_facts"}'));

        $client = new OllamaLegalAssistantClient($httpClient, $promptResolver, ['qwen' => 'qwen3:32b'], 'qwen');

        self::assertSame(['stage' => 'collect_facts'], $client->chatBase(
            model: 'custom-model',
            currentJson: ['facts' => []],
            clientMessage: 'Привет',
        ));
    }

    public function test_it_appends_secondary_prompt_views_after_base_prompts(): void
    {
        /** @var OllamaHttpClientInterface&MockInterface $httpClient */
        $httpClient = Mockery::mock(OllamaHttpClientInterface::class);
        /** @var PromptResolverInterface&MockInterface $promptResolver */
        $promptResolver = Mockery::mock(PromptResolverInterface::class);

        /** @var Expectation $messagesExpectation */
        $messagesExpectation = $promptResolver->shouldReceive('resolveMessages');
        $messagesExpectation
            ->once()
            ->with([
                [
                    'role' => 'system',
                    'views' => [
                        'components.ai_prompts.ollama.base.system',
                        'components.ai_prompts.ollama.secondary.system',
                    ],
                ],
                [
                    'role' => 'user',
                    'views' => [
                        'components.ai_prompts.ollama.base.user',
                        'components.ai_prompts.ollama.survey.user',
                    ],
                ],
            ], [
                'surveyStep' => 'contact',
                'currentJson' => '{"facts":[]}',
                'clientMessage' => 'Здравствуйте',
            ])
            ->andReturn([
                ['role' => 'system', 'content' => 'system content'],
                ['role' => 'user', 'content' => 'user content'],
            ]);

        /** @var Expectation $chatExpectation */
        $chatExpectation = $httpClient->shouldReceive('chat');
        $chatExpectation
            ->once()
            ->with('qwen3:32b', [
                ['role' => 'system', 'content' => 'system content'],
                ['role' => 'user', 'content' => 'user content'],
            ], [])
            ->andReturn(new Response(200, [], '{"stage":"collect_contact"}'));

        $client = new OllamaLegalAssistantClient($httpClient, $promptResolver, ['qwen' => 'qwen3:32b'], 'qwen');

        self::assertSame(['stage' => 'collect_contact'], $client->chatBase(
            model: 'qwen',
            currentJson: ['facts' => []],
            clientMessage: 'Здравствуйте',
            systemPromptViews: ['components.ai_prompts.ollama.secondary.system'],
            userPromptViews: ['components.ai_prompts.ollama.survey.user'],
            promptData: ['surveyStep' => 'contact'],
        ));
    }
}
