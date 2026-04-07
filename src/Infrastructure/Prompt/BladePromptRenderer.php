<?php

declare(strict_types=1);

namespace Infrastructure\Prompt;

use Application\Port\Prompt\PromptRendererInterface;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Override;

final readonly class BladePromptRenderer implements PromptRendererInterface
{
    public function __construct(
        private ViewFactory $viewFactory,
    ) {
    }

    #[Override]
    public function resolveView(string $view, array $data = []): string
    {
        return trim($this->viewFactory->make($view, $data)->render());
    }
}
