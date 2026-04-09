<?php

declare(strict_types=1);

namespace Application\Port\Prompt;

interface PromptResolverInterface
{
    /**
     * @param list<string> $views
     * @param array<string, mixed> $data
     */
    public function resolveViews(array $views, array $data = []): string;

    /**
     * @param list<array{role:string,views:list<string>}> $messages
     * @param array<string, mixed> $data
     *
     * @return list<array{role:string,content:string}>
     */
    public function resolveMessages(array $messages, array $data = []): array;
}
