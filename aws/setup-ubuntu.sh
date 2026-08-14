#!/usr/bin/env bash
set -euo pipefail

APP_DIR="/opt/stockmarket-inventario"
REPO_URL="https://github.com/maytenunez1-ui/StockMarket-Inventario.git"

sudo apt-get update
sudo apt-get install -y ca-certificates curl git
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo \"$VERSION_CODENAME\") stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

if [ ! -d "$APP_DIR/.git" ]; then
  sudo git clone "$REPO_URL" "$APP_DIR"
else
  sudo git -C "$APP_DIR" pull --ff-only
fi
sudo chown -R "$USER":"$USER" "$APP_DIR"
cd "$APP_DIR"

if [ ! -f .env ]; then
  cp .env.aws.example .env
  echo "Edita $APP_DIR/.env con DATABASE_URL y JWT_SECRET, después ejecuta: docker compose up -d --build"
  exit 0
fi

docker compose up -d --build
docker compose ps
