#!/bin/bash

# EC NEXT 'Always-Latest' Synchronization Script
# This script ensures the framework, packages, and assets are kept up-to-date.

echo "--- 1. Syncing PHP Dependencies (Composer) ---"
export PATH=$PATH:/opt/homebrew/bin:/usr/local/bin
/Applications/MAMP/bin/php/php8.4.1/bin/php /opt/homebrew/bin/composer update -W --no-interaction

echo "--- 2. Syncing Node Dependencies (NPM) ---"
/usr/local/bin/npm update

echo "--- 3. Running Database Migrations ---"
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan migrate --force

echo "--- 4. Rebuilding Frontend Assets ---"
/usr/local/bin/npm run build

echo "--- Environment Updated Successfully ---"
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan --version
