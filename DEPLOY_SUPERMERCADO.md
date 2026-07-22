# Despliegue en la nube

Este proyecto ya esta preparado como sistema de supermercado. Para conectarlo a un servidor en la nube necesitas un hosting compatible con PHP/Laravel y una base de datos MySQL.

## Datos que necesitas del servidor

- Dominio o URL del sitio
- Host de MySQL
- Nombre de la base de datos
- Usuario de la base de datos
- Contrasena de la base de datos
- Version de PHP compatible con el proyecto

## Pasos basicos

1. Sube el proyecto al hosting.
2. Copia `.env.cloud.example` como `.env`.
3. Llena `APP_URL`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`.
4. Ejecuta:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Usuarios demo

Cuando ejecutes `php artisan migrate --seed`, se crean:

- Admin: `admin@supermercado.test`
- Cliente: `cliente@supermercado.test`
- Contrasena: `password`

Despues del despliegue conviene cambiar esas contrasenas.
