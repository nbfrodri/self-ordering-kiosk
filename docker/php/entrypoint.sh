#!/bin/sh
set -e

# ---------------------------------------------------------------------------
# Wait helper — gives dependent services a moment to be fully ready even
# after the healthcheck passes (e.g. MySQL accepting connections vs ready
# for DDL).  Adjust MAX_WAIT as needed.
# ---------------------------------------------------------------------------
wait_for() {
    host="$1"
    port="$2"
    label="$3"
    max_wait=60
    elapsed=0

    echo "[entrypoint] Waiting for ${label} at ${host}:${port}..."
    while ! nc -z "$host" "$port" 2>/dev/null; do
        if [ "$elapsed" -ge "$max_wait" ]; then
            echo "[entrypoint] ERROR: Timed out waiting for ${label}." >&2
            exit 1
        fi
        sleep 2
        elapsed=$((elapsed + 2))
    done
    echo "[entrypoint] ${label} is reachable."
}

# ---------------------------------------------------------------------------
# 1. Install Composer dependencies when vendor/ is absent (fresh clone or
#    first-time container start without a pre-built image).
# ---------------------------------------------------------------------------
if [ ! -f "vendor/autoload.php" ]; then
    echo "[entrypoint] vendor/autoload.php not found — running composer update (no-dev)..."
    composer update --no-dev --no-interaction --prefer-dist --optimize-autoloader
fi

# ---------------------------------------------------------------------------
# 2. Bootstrap .env from the example template when no .env file exists.
# ---------------------------------------------------------------------------
if [ ! -f ".env" ]; then
    echo "[entrypoint] .env not found — copying from .env.example..."
    cp .env.example .env
fi

# ---------------------------------------------------------------------------
# 3. Generate application key when APP_KEY is empty.
# ---------------------------------------------------------------------------
if grep -q "^APP_KEY=$" .env || ! grep -q "^APP_KEY=" .env; then
    echo "[entrypoint] Generating application key..."
    php artisan key:generate --force
fi

# ---------------------------------------------------------------------------
# 4. Wait for data stores before running schema operations.
# ---------------------------------------------------------------------------
wait_for "${DB_HOST:-mysql}"    "${DB_PORT:-3306}"  "MySQL"
wait_for "${MONGO_HOST:-mongodb}" "${MONGO_PORT:-27017}" "MongoDB"

# ---------------------------------------------------------------------------
# 5. Run migrations and seeders.
#    --force is required to allow migrations to run in non-local environments.
# ---------------------------------------------------------------------------
echo "[entrypoint] Running database migrations..."
php artisan migrate --force

# Only seed if the categories table is empty (avoid duplicates on restart)
CATEGORY_COUNT=$(php artisan tinker --execute="echo \App\Models\Category::count();" 2>/dev/null || echo "0")
if [ "$CATEGORY_COUNT" = "0" ]; then
    echo "[entrypoint] Seeding database..."
    php artisan db:seed --force
else
    echo "[entrypoint] Database already seeded (${CATEGORY_COUNT} categories found), skipping."
fi

# ---------------------------------------------------------------------------
# 6. Clear and warm caches so the application starts with clean state.
# ---------------------------------------------------------------------------
echo "[entrypoint] Clearing and caching config..."
php artisan config:clear
php artisan cache:clear

# ---------------------------------------------------------------------------
# 7. Hand off to the CMD supplied by docker-compose (php-fpm).
# ---------------------------------------------------------------------------
echo "[entrypoint] Starting php-fpm..."
exec "$@"
