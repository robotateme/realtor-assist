<?php

declare(strict_types=1);

return [
    'queues' => [
        'llm' => env('QUEUE_LLM', 'llm'),
        'webhook' => env('QUEUE_WEBHOOK', 'webhook'),
    ],
    'cache' => [
        'store' => env('REALTOR_ASSIST_CACHE_STORE', env('CACHE_STORE', 'database')),
        'prefix' => env('REALTOR_ASSIST_CACHE_PREFIX', 'realtor-assist'),
        'default_ttl' => env('REALTOR_ASSIST_CACHE_TTL'),
    ],
    'redis' => [
        'connection' => env('REALTOR_ASSIST_REDIS_CONNECTION', 'default'),
    ],
    'rate_limit' => [
        'prefix' => env('REALTOR_ASSIST_RATE_LIMIT_PREFIX', 'rate-limit'),
        'ollama' => [
            'enabled' => (bool) env('OLLAMA_RATE_LIMIT_ENABLED', true),
            'bucket' => env('OLLAMA_RATE_LIMIT_BUCKET', 'ollama:chat'),
            'max_attempts' => (int) env('OLLAMA_RATE_LIMIT_MAX_ATTEMPTS', 30),
            'window_seconds' => (int) env('OLLAMA_RATE_LIMIT_WINDOW_SECONDS', 60),
            'cost' => (int) env('OLLAMA_RATE_LIMIT_COST', 1),
        ],
    ],
    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'https://api.ollama.ai'),
        'api_key' => env('OLLAMA_API_KEY'),
        'timeout' => (float) env('OLLAMA_TIMEOUT', 60),
        'connect_timeout' => (float) env('OLLAMA_CONNECT_TIMEOUT', 10),
        'default_model' => env('OLLAMA_DEFAULT_MODEL', 'qwen'),
        'models' => [
            'qwen' => env('OLLAMA_MODEL_QWEN', 'qwen3:32b'),
            'gpt' => env('OLLAMA_MODEL_GPT', 'gpt-oss:120b'),
        ],
    ],
];
