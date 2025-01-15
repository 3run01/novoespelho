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
    && docker-php-ext-install pdo pdo_pgsql


WORKDIR /var/www

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY . .

RUN npm install
RUN npm run build


CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
