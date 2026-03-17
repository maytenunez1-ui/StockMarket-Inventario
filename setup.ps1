param(
    [switch]$SkipComposer,
    [switch]$SkipFrontend,
    [switch]$SkipMigrate,
    [switch]$ForceEnvCopy
)

$ErrorActionPreference = 'Stop'

function Assert-Command {
    param([string]$Name)
    if (-not (Get-Command $Name -ErrorAction SilentlyContinue)) {
        throw "No se encontro el comando requerido: $Name"
    }
}

Write-Host "==> Iniciando setup del proyecto Laravel" -ForegroundColor Cyan

Assert-Command php

if (-not $SkipComposer) {
    Assert-Command composer
    Write-Host "==> Instalando dependencias PHP (composer install)" -ForegroundColor Yellow
    composer install --no-interaction --prefer-dist
} else {
    Write-Host "==> Omitiendo composer install" -ForegroundColor DarkYellow
}

if ($ForceEnvCopy -or -not (Test-Path '.env')) {
    Write-Host "==> Creando .env desde .env.example" -ForegroundColor Yellow
    Copy-Item '.env.example' '.env' -Force
} else {
    Write-Host "==> .env ya existe, se conserva" -ForegroundColor DarkYellow
}

$envHasAppKey = $false
if (Test-Path '.env') {
    $appKeyLine = Select-String -Path '.env' -Pattern '^APP_KEY=' | Select-Object -First 1
    if ($appKeyLine) {
        $value = ($appKeyLine.Line -replace '^APP_KEY=', '').Trim()
        $envHasAppKey = -not [string]::IsNullOrWhiteSpace($value)
    }
}

if (-not $envHasAppKey) {
    Write-Host "==> Generando APP_KEY" -ForegroundColor Yellow
    php artisan key:generate --ansi
} else {
    Write-Host "==> APP_KEY ya existe, no se regenera" -ForegroundColor DarkYellow
}

Write-Host "==> Limpiando cache/config" -ForegroundColor Yellow
php artisan optimize:clear --ansi

if (-not $SkipMigrate) {
    Write-Host "==> Ejecutando migraciones" -ForegroundColor Yellow
    php artisan migrate --ansi --force
} else {
    Write-Host "==> Omitiendo migraciones" -ForegroundColor DarkYellow
}

if (-not $SkipFrontend) {
    Assert-Command npm
    Write-Host "==> Instalando dependencias frontend (npm install)" -ForegroundColor Yellow
    npm install
    Write-Host "==> Compilando assets (npm run build)" -ForegroundColor Yellow
    npm run build
} else {
    Write-Host "==> Omitiendo instalacion frontend" -ForegroundColor DarkYellow
}

Write-Host "==> Setup completado" -ForegroundColor Green
Write-Host "Para correr el proyecto: php artisan serve" -ForegroundColor Green
