FROM docker.io/library/php:8.3-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    mariadb-client \
    postgresql-client

# Limpa cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões PHP necessárias para Laravel e PostgreSQL
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instala o Composer mais recente
COPY --from=docker.io/library/composer:latest /usr/bin/composer /usr/bin/composer

# Instala o Node.js e NPM (necessário para Inertia/Vue)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Define diretório de trabalho
WORKDIR /var/www
