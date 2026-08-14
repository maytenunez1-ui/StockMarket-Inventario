# Despliegue en AWS — StockMarket Inventario

La aplicación usa **Node.js**, **PostgreSQL** y seis microservicios. Para el entorno de pruebas, el diseño de despliegue es:

```text
Internet → EC2 (Nginx/Docker) → microservicios Node.js + PostgreSQL
```

Solo EC2 publica el puerto 80; las API y la base de datos quedan dentro de la red privada de Docker.

## 1. Recursos en AWS

1. Crea una instancia **EC2 Ubuntu 24.04** (t3.micro para pruebas).
2. Configura los grupos de seguridad:
   - EC2: HTTP `80` desde Internet y SSH `22` únicamente desde tu IP.
3. Opcional pero recomendable: asigna una Elastic IP a EC2.

## 2. Instalar y ejecutar en EC2

Conéctate a EC2 y ejecuta:

```bash
git clone https://github.com/maytenunez1-ui/StockMarket-Inventario.git /opt/stockmarket-inventario
cd /opt/stockmarket-inventario
chmod +x aws/setup-ubuntu.sh
./aws/setup-ubuntu.sh
```

El primer uso instala Docker y crea `.env`. Edita ese archivo:

```bash
nano /opt/stockmarket-inventario/.env
```

Configura `POSTGRES_PASSWORD` y `JWT_SECRET` con valores largos, únicos y privados. Después inicia la aplicación:

```bash
docker compose up -d --build
docker compose ps
```

Abre `http://IP_PUBLICA_EC2`. El usuario inicial es `admin@stockmarket.com` y la contraseña es `1234`; cámbiala antes de exponer la aplicación.

## Actualizaciones

```bash
cd /opt/stockmarket-inventario
git pull --ff-only
docker compose up -d --build
```

No subas `.env`, llaves SSH ni contraseñas de RDS a GitHub.
