# shellcheck shell=bash

if command -v php >/dev/null 2>&1 && [ -f /var/www/html/artisan ]; then
    eval "$(
        cd /var/www/html &&
        command php artisan completion bash
    )"
fi
