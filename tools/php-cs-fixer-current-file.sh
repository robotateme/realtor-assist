#!/usr/bin/env bash

set -euo pipefail

PROJECT_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
FILE_PATH="${1:-}"

if [[ -z "$FILE_PATH" ]]; then
    echo "Missing file path for PHP CS Fixer watcher." >&2
    exit 1
fi

cd "$PROJECT_DIR"

if docker compose ps --status running --services 2>/dev/null | grep -qx 'laravel.test'; then
    exec ./vendor/bin/sail php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php "$FILE_PATH"
fi

exec php vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php "$FILE_PATH"
