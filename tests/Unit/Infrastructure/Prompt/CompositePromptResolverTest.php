<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Prompt;

use Application\Port\Prompt\PromptRendererInterface;
use Infrastructure\Prompt\CompositePromptResolver;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class CompositePromptResolverTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_resolves_multiple_views_into_single_prompt(): void
    {
        /** @var PromptRendererInterface&MockInterface $renderer */
        $renderer = Mockery::mock(PromptRendererInterface::class);

        /** @var Expectation $baseExpectation */
        $baseExpectation = $renderer->shouldReceive('resolveView');
        $baseExpectation->once()->with('base', ['foo' => 'bar'])->andReturn('base prompt');

        /** @var Expectation $secondaryExpectation */
        $secondaryExpectation = $renderer->shouldReceive('resolveView');
        $secondaryExpectation->once()->with('secondary', ['foo' => 'bar'])->andReturn('secondary prompt');

        $resolver = new CompositePromptResolver($renderer);

        self::assertSame("base prompt\n\nsecondary prompt", $resolver->resolveViews(
            ['base', 'secondary'],
            ['foo' => 'bar'],
        ));
    }

    public function test_it_resolves_chat_messages_from_prompt_layers(): void
    {
        /** @var PromptRendererInterface&MockInterface $renderer */
        $renderer = Mockery::mock(PromptRendererInterface::class);

        /** @var Expectation $systemBaseExpectation */
        $systemBaseExpectation = $renderer->shouldReceive('resolveView');
        $systemBaseExpectation->once()->with('system.base', [])->andReturn('system base');

        /** @var Expectation $systemFollowupExpectation */
        $systemFollowupExpectation = $renderer->shouldReceive('resolveView');
        $systemFollowupExpectation->once()->with('system.followup', [])->andReturn('system followup');

        /** @var Expectation $userBaseExpectation */
        $userBaseExpectation = $renderer->shouldReceive('resolveView');
        $userBaseExpectation->once()->with('user.base', [])->andReturn('user base');

        $resolver = new CompositePromptResolver($renderer);

        self::assertSame([
            [
                'role' => 'system',
                'content' => "system base\n\nsystem followup",
            ],
            [
                'role' => 'user',
                'content' => 'user base',
            ],
        ], $resolver->resolveMessages([
            ['role' => 'system', 'views' => ['system.base', 'system.followup']],
            ['role' => 'user', 'views' => ['user.base']],
        ]));
    }
}
