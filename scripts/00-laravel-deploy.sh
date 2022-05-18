#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo

echo "Upgrading Composer"
composer self-update -vvv

composer install --no-dev --working-dir=/var/www/html

echo "Set Permission"
sudo chmod -R 777 /var/www/html/storage
sudo chmod -R 775 /var/www/html/bootstrap
php artisan optimize

#echo "Caching config..."
#php artisan config:cache
#
#echo "Caching routes..."
#php artisan route:cache

#echo "Running migrations..."
#php artisan migrate --force

echo "Running serve"
php artisan serve
