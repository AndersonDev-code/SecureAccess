FROM php:8.2-fpm

# Installation des dépendances système, Nginx, Python 3 et bibliothèques pour OpenCV
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nginx \
    python3 \
    python3-pip \
    python3-venv \
    libgl1 \
    libglib2.0-0

# Installation des extensions PHP (PostgreSQL + MySQL)
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring exif pcntl bcmath gd

# Configuration de l'environnement virtuel Python et installation des paquets
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"

# Installation des librairies Python pour la reconnaissance faciale
RUN pip install --no-cache-dir \
    numpy \
    opencv-python-headless \
    mediapipe

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Permissions pour le stockage Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Configuration Nginx basique pour Laravel
RUN echo 'server { \
    listen 80; \
    index index.php index.html; \
    root /var/www/public; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        include fastcgi_params; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/sites-available/default

# Copie et configuration du script de démarrage
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["docker-entrypoint.sh"]