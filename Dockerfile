# -----------------------------
# Stage 1 - Build Frontend (Vite)
# -----------------------------
FROM node:18 AS frontend

WORKDIR /app

# Copy package files and install dependencies
COPY package*.json ./
RUN npm install

# Copy all source code
COPY . .

# Build frontend (Vite)
RUN npm run build

# Verify build output
RUN if [ -d "/app/public/dist" ]; then ls -al /app/public/dist; else echo "Build folder not found!"; fi

# -----------------------------
# Stage 2 - Backend (Laravel + PHP + Composer)
# -----------------------------
FROM php:8.2-fpm AS backend

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpq-dev libonig-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel app
COPY . .

# Copy frontend build from Stage 1
COPY --from=frontend /app/public/dist ./public/dist

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Laravel cache clear
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Expose port and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
