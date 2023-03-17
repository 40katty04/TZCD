FROM ubuntu:22.04

USER root

# Update all packages
RUN apt-get update

# Tools
RUN apt-get install -yq --no-install-recommends \
    git \
    apt-utils \
    curl \
    openssl \
    iputils-ping \
    nano \
    ca-certificates
# Nginx
RUN apt-get install -yq --no-install-recommends nginx


# Install PHP and related packages interactively
RUN DEBIAN_FRONTEND=noninteractive apt-get install -yq --no-install-recommends \
    php8.1 \
    php8.1-dom \
    php8.1-fpm \
    php8.1-curl \
    php8.1-mbstring \
    php8.1-mysql \
    php8.1-xml \
    php8.1-zip \
    php8.1-pdo \
    php8.1-mysql \
    php8.1-cli \
    mysql-client

# Install composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www

# set polices
RUN chown -R www-data:www-data /var/www/

CMD service php8.1-fpm start ; cp .env.example .env; composer install; php artisan key:generate; php artisan migrate; nginx -g 'daemon off;' ;

