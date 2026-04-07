<?php

declare(strict_types=1);

return [
    'queues' => [
        'llm' => env('QUEUE_LLM', 'llm'),
        'webhook' => env('QUEUE_WEBHOOK', 'webhook'),
    ],
    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'http://127.0.0.1:11434'),
        'timeout' => (float) env('OLLAMA_TIMEOUT', 60),
        'connect_timeout' => (float) env('OLLAMA_CONNECT_TIMEOUT', 10),
    ],
];
