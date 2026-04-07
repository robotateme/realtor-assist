<?php

declare(strict_types=1);

namespace Application\Port\Prompt;

interface PromptRendererInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function resolveView(string $view, array $data = []): string;
}
