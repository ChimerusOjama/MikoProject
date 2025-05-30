#!/usr/bin/env bash
# build and launch script for Render

echo "Lancement du script de build Laravel..."

composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=$PORT
