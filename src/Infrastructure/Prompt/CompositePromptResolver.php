<?php

declare(strict_types=1);

namespace Infrastructure\Prompt;

use Application\Port\Prompt\PromptRendererInterface;
use Application\Port\Prompt\PromptResolverInterface;
use Override;

final readonly class CompositePromptResolver implements PromptResolverInterface
{
    public function __construct(
        private PromptRendererInterface $promptRenderer,
    ) {
    }

    #[Override]
    public function resolveViews(array $views, array $data = []): string
    {
        $resolvedViews = [];

        foreach ($views as $view) {
            $prompt = $this->promptRenderer->resolveView($view, $data);

            if ($prompt === '') {
                continue;
            }

            $resolvedViews[] = $prompt;
        }

        return implode("\n\n", $resolvedViews);
    }

    #[Override]
    public function resolveMessages(array $messages, array $data = []): array
    {
        $resolvedMessages = [];

        foreach ($messages as $message) {
            $content = $this->resolveViews($message['views'], $data);

            if ($content === '') {
                continue;
            }

            $resolvedMessages[] = [
                'role' => $message['role'],
                'content' => $content,
            ];
        }

        return $resolvedMessages;
    }
}
