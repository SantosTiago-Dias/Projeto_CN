#!/bin/bash
set -e

if [ "${RUN_MIGRATIONS}" = "true" ]; then
  echo "Waiting for database..."
  until nc -z "$DB_HOST" "${DB_PORT:-3306}"; do
    echo "DB not ready, retrying..."
    sleep 2
  done

  echo "Running migrations..."
  php artisan migrate --force --no-interaction

  FLAG="/app/storage/.seeded"

  if [ ! -f "$FLAG" ]; then
    echo "No seed flag found, running seeders..."
    touch "$FLAG"
    php artisan db:seed --force --no-interaction || {
      echo "Seeder failed but continuing startup..."
    }
    echo "Seeding done."
  else
    echo "Already seeded, skipping."
  fi
fi

echo "Starting server..."
exec "$@"
