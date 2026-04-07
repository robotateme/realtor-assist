<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Prompt;

use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Infrastructure\Prompt\BladePromptRenderer;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Expectation;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

final class BladePromptRendererTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_it_resolves_blade_view_into_trimmed_prompt(): void
    {
        /** @var ViewFactory&MockInterface $viewFactory */
        $viewFactory = Mockery::mock(ViewFactory::class);
        /** @var View&MockInterface $view */
        $view = Mockery::mock(View::class);

        /** @var Expectation $viewExpectation */
        $viewExpectation = $viewFactory->shouldReceive('make');
        $viewExpectation
            ->once()
            ->with('components.ai_prompts.ollama.base.user', ['foo' => 'bar'])
            ->andReturn($view);

        /** @var Expectation $renderExpectation */
        $renderExpectation = $view->shouldReceive('render');
        $renderExpectation->once()->andReturn("\n prompt body \n");

        $renderer = new BladePromptRenderer($viewFactory);

        self::assertSame('prompt body', $renderer->resolveView(
            'components.ai_prompts.ollama.base.user',
            ['foo' => 'bar'],
        ));
    }
}
