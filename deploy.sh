#!/bin/bash

# Script de deployment para el servidor de producción

echo "🚀 Iniciando deployment..."

# Instalar dependencias
echo "📦 Instalando dependencias..."
npm install --legacy-peer-deps

# Compilar assets
echo "🔨 Compilando assets..."
npm run build

# Limpiar cachés de Laravel
echo "🧹 Limpiando cachés..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimizar para producción
echo "⚡ Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo "✅ Deployment completado!"
