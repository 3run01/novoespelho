FROM dunglas/frankenphp:php8.3-bookworm

WORKDIR /var/www/app

RUN apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    git \
    unzip \
    librabbitmq-dev \
    libpq-dev \
    supervisor \
    libpng-dev \
    libxml2-dev \
    zlib1g-dev \
    libzip-dev \
    libxext-dev \
    ghostscript \
    libxrender1 \
    openssl \
    wget \
    && rm -rf /var/lib/apt/lists/*

RUN install-php-extensions \
    gd \
    pcntl \
    opcache \
    pdo \
    pdo_mysql \
    redis

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql pgsql session xml zip iconv simplexml pcntl gd fileinfo intl soap sockets bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instala Node.js 22 e npm globalmente
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g pm2

RUN wget https://github.com/elastic/apm-agent-php/releases/download/v1.15.0/apm-agent-php_1.15.0_amd64.deb && apt install -y ./apm-agent-php_1.15.0_amd64.deb && rm ./apm-agent-php_1.15.0_amd64.deb

# Habilita o provider legacy do OpenSSL 3.x
RUN sed -i -e '$a\' /etc/ssl/openssl.cnf
RUN printf "\n[openssl_init]\nproviders = provider_sect\n\n[provider_sect]\ndefault = default_sect\nlegacy = legacy_sect\n\n[default_sect]\nactivate = 1\n\n[legacy_sect]\nactivate = 1\n" >> /etc/ssl/openssl.cnf

# Cria diretórios necessários para o supervisord
RUN mkdir -p /var/log/supervisor /etc/supervisord.d

# Define o comando para iniciar o supervisord
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
