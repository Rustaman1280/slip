#!/bin/sh
set -e

# 1. Sesuaikan Port Nginx dengan environment variable $PORT dari Render (default 10000 atau 80)
PORT="${PORT:-10000}"
echo "==> Mengonfigurasi Nginx untuk mendengarkan port $PORT..."
sed -i "s/PORT_PLACEHOLDER/${PORT}/g" /etc/nginx/http.d/default.conf

# 2. Pastikan direktori storage & bootstrap/cache ada dan permission-nya tepat
echo "==> Memeriksa direktori storage dan cache..."
mkdir -p /var/www/html/storage/framework/cache \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Buat symlink storage
php artisan storage:link --force || true

# 4. Cache konfigurasi jika APP_KEY tersedia
if [ -n "$APP_KEY" ]; then
    echo "==> Melakukan cache config, route, dan view..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
else
    echo "==> PERINGATAN: APP_KEY belum diatur! Pastikan mengisi APP_KEY di Environment Variables Render."
fi

# 5. Jalankan migrasi database otomatis jika diaktifkan (default: true)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "==> Menjalankan migrasi database..."
    php artisan migrate --force || echo "==> Info: Migrasi dilewati / database belum dapat diakses."

    if [ "${RUN_SEEDER:-false}" = "true" ]; then
        echo "==> Menjalankan database seeders..."
        php artisan db:seed --force || echo "==> Info: Seeder gagal atau sudah ada."
    fi
fi

# 6. Jalankan Supervisord (Nginx + PHP-FPM)
echo "==> Memulai Web Server (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
