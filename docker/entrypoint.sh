#!/bin/sh
set -e

# Si se pasa un comando (por ejemplo las migraciones), se ejecuta tal cual
if [ "$#" -gt 0 ]; then
  exec "$@"
fi

# Arranque normal del servidor de aplicaciones
cd /var/www/html
php artisan config:cache
php artisan route:cache
chown -R www-data:www-data storage bootstrap/cache

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
