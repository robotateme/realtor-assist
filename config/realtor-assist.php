<?php

declare(strict_types=1);

return [
    'queues' => [
        'llm' => env('QUEUE_LLM', 'llm'),
        'webhook' => env('QUEUE_WEBHOOK', 'webhook'),
    ],
];
