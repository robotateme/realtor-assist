# shellcheck shell=bash

if command -v php >/dev/null 2>&1 && [ -f /home/oem/Work/realtor-assist/artisan ]; then
    eval "$(
        cd /home/oem/Work/realtor-assist &&
        command php artisan completion bash
    )"
fi
