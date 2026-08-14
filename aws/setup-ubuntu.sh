#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/var/www/supermercado-ujcv"
REPO_URL="https://github.com/amnimaca01-del/Biblioteca.git"

sudo apt update
sudo apt install -y nginx git unzip curl software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-bcmath php8.3-tokenizer

if ! command -v composer >/dev/null 2>&1; then
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
    sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
fi

sudo mkdir -p "$APP_DIR"
sudo chown -R "$USER":"$USER" "$APP_DIR"

if [ ! -d "$APP_DIR/.git" ]; then
    git clone "$REPO_URL" "$APP_DIR"
else
    cd "$APP_DIR"
    git pull
fi

cd "$APP_DIR"
composer install --no-dev --optimize-autoloader

if [ ! -f .env ]; then
    cp .env.aws.example .env
    echo "Edita $APP_DIR/.env con APP_URL y datos de RDS antes de continuar."
fi

php artisan key:generate --force
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache

sudo cp aws/nginx-supermercado.conf /etc/nginx/sites-available/supermercado-ujcv
sudo ln -sf /etc/nginx/sites-available/supermercado-ujcv /etc/nginx/sites-enabled/supermercado-ujcv
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx

echo "Servidor listo. Ahora configura .env y ejecuta:"
echo "php artisan migrate --seed --force"
echo "php artisan config:cache"
