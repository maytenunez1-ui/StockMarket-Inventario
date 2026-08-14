# Despliegue en AWS para Supermercado UJCV

La forma recomendada para este proyecto Laravel es:

- **EC2 Ubuntu** para alojar la aplicacion.
- **RDS MySQL** para la base de datos.
- **Security Group** permitiendo HTTP `80`, HTTPS `443` y SSH `22`.
- **Elastic IP** opcional para que la IP no cambie.

## 1. Crear la instancia EC2

1. En AWS, entra a EC2.
2. Crea una instancia Ubuntu.
3. Tipo sugerido para pruebas: `t2.micro` o `t3.micro` si esta disponible en tu cuenta.
4. Abre puertos:
   - `22` para SSH
   - `80` para HTTP
   - `443` para HTTPS si usaras certificado

## 2. Crear RDS MySQL

1. En AWS, entra a RDS.
2. Crea una base MySQL.
3. Nombre sugerido de base: `supermercado_ujcv`.
4. Guarda:
   - endpoint
   - usuario
   - contrasena
5. En el Security Group de RDS, permite conexion MySQL `3306` desde el Security Group de EC2.

## 3. Subir el proyecto a EC2

Conectate por SSH a EC2 y ejecuta:

```bash
git clone https://github.com/amnimaca01-del/Biblioteca.git /var/www/supermercado-ujcv
cd /var/www/supermercado-ujcv
chmod +x aws/setup-ubuntu.sh
./aws/setup-ubuntu.sh
```

## 4. Configurar `.env`

Edita:

```bash
nano /var/www/supermercado-ujcv/.env
```

Llena:

```env
APP_URL=http://TU_IP_PUBLICA_O_DOMINIO
DB_HOST=TU_ENDPOINT_RDS
DB_DATABASE=supermercado_ujcv
DB_USERNAME=admin
DB_PASSWORD=TU_PASSWORD_RDS
```

## 5. Crear tablas y productos hondurenos

Ejecuta:

```bash
php artisan migrate --seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 6. Ver el proyecto

Abre en el navegador:

```text
http://TU_IP_PUBLICA
```

Si tienes dominio, apunta el DNS a la Elastic IP de EC2.

## Usuarios demo

Despues de `migrate --seed`:

- Admin: `admin@supermercado.test`
- Cliente: `cliente@supermercado.test`
- Contrasena: `password`

Cambia esas contrasenas antes de entregar el proyecto.
