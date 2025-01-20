FROM php:8.2

RUN apt-get update && apt-get install -y \ 
    libpq-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpng-dev \
    libxml2-dev \
    nodejs \
    npm \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql zip intl gd \
    && docker-php-ext-configure intl

WORKDIR /var/www

# Copiar composer.json e composer.lock primeiro
COPY composer.json composer.lock ./

# Instalar dependências do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Copiar package.json e package-lock.json
COPY package.json package-lock.json ./

# Instalar dependências do Node
RUN npm install

# Copiar o resto do código
COPY . .

# Finalizar instalação do Composer
RUN composer dump-autoload --optimize

# Build dos assets
RUN npm run build

CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]