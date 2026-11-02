#!/usr/bin/env bash
# Update the live site after new code is in /var/www/towercircle (git pull). Safe to run on every update.
# Usage:  cd /var/www/towercircle && bash deploy/deploy.sh      (as the normal "ubuntu" user, not root)
#
# The site goes into maintenance mode while it updates. If every step works, it comes back up.
# If a step FAILS, the site deliberately STAYS in maintenance mode: bringing half-updated code back up
# (missing packages, a half-run migration) could show visitors errors or save wrong data.
# See README → "If an update fails" for what to do then.
set -euo pipefail
cd "$(dirname "$0")/.."

finish() {
    local status=$?
    # Hand the folders back to the web server whatever happened.
    sudo chown -R www-data:www-data storage bootstrap/cache || true
    if [ "$status" -eq 0 ]; then
        sudo -u www-data php artisan up
        echo "Deployed. The site is live."
    else
        echo ""
        echo "!! The update FAILED (exit code $status). The site is still in MAINTENANCE MODE on purpose."
        echo "!! Read the error above, fix it, and run this script again."
        echo "!! To go back to the previous version instead: git checkout <previous commit> && bash deploy/deploy.sh"
        echo "!! Only if you are sure the live code and database are fine: sudo -u www-data php artisan up"
    fi
}
trap finish EXIT

# While deploying, this user needs to write to these folders; they go back to www-data at the end.
sudo chown -R "$USER":www-data storage bootstrap/cache public
sudo chmod -R ug+rwX storage bootstrap/cache public
# The web user must be able to read .env, and nobody else (it holds the passwords).
sudo chown "$USER":www-data .env
sudo chmod 640 .env

php artisan down --retry=30 || true
composer install --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear
# Stop here (still in maintenance mode) if the .env is missing something important.
php artisan migrate --force
php artisan storage:link || true
php artisan filament:assets
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize || true
sudo systemctl restart php8.3-fpm
