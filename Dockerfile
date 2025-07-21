# Multi-stage Dockerfile para Laravel - Sistema de Onboarding HCP
# Estágio 1: Build de dependências
FROM php:8.2-fpm-alpine AS php-base

# Instalar dependências do sistema
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    oniguruma-dev \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev

# Instalar extensões PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos de configuração do Composer
COPY composer.json composer.lock ./

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Estágio 2: Build de assets frontend
FROM node:18-alpine AS node-build

WORKDIR /var/www/html

# Copiar arquivos de configuração do Node
COPY package.json package-lock.json ./
COPY vite.config.js tailwind.config.js postcss.config.js ./

# Instalar dependências Node
RUN npm ci --only=production

# Copiar arquivos de recursos
COPY resources/ resources/

# Build dos assets
RUN npm run build

# Estágio 3: Imagem final de produção
FROM php:8.2-fpm-alpine AS production

# Instalar dependências mínimas para produção
RUN apk add --no-cache \
    libpng \
    libxml2 \
    oniguruma \
    postgresql-libs \
    freetype \
    libjpeg-turbo \
    libwebp \
    nginx \
    supervisor

# Instalar extensões PHP (mesmo do estágio base)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    zip

# Configurar usuário não-root
RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar aplicação do estágio de build
COPY --from=php-base --chown=www:www /var/www/html/vendor ./vendor
COPY --from=node-build --chown=www:www /var/www/html/public/build ./public/build

# Copiar código da aplicação
COPY --chown=www:www . .

# Configurar permissões
RUN chown -R www:www /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Copiar configurações do Nginx e Supervisor
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expor porta
EXPOSE 80

# Comando de inicialização
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Estágio de desenvolvimento
FROM php-base AS development

# Instalar dependências de desenvolvimento
RUN composer install --optimize-autoloader

# Instalar Xdebug para desenvolvimento
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurar Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Configurar usuário
USER www

# Comando para desenvolvimento
CMD ["php-fpm"]