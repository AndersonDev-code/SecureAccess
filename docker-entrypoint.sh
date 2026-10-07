#!/bin/sh
set -e

# Exécuter les migrations
php artisan migrate --force &

# Créer le lien symbolique du storage public
php artisan storage:link --force

# Démarrer PHP-FPM et Nginx
php-fpm &
exec nginx -g "daemon off;"