#!/bin/sh
set -e

# Exécuter les migrations en arrière-plan sans bloquer le démarrage du serveur
php artisan migrate:fresh --force &

# Démarrer PHP-FPM et Nginx
php-fpm &
exec nginx -g "daemon off;"