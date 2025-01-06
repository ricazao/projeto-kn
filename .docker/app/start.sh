#!/bin/sh

# Install PHP dependencies
composer install --no-interaction --no-progress --prefer-dist

# Run migrations
php artisan migrate --force

# Start the server
apache2-foreground
