FROM php:7.4.3-apache-buster

# Enable rewrite-mod
RUN a2enmod rewrite

# Install PHP extensions
RUN apt-get update && \
    apt-get install -y zlib1g-dev libicu-dev g++ libxml2-dev libzip-dev unzip && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip && \
    docker-php-ext-configure pdo_mysql && \
    docker-php-ext-install pdo_mysql

# Set Timezone & localtime in docker
RUN echo "Europe/Madrid" > /etc/timezone && \
    rm /etc/localtime && \
    ln -s /usr/share/zoneinfo/Europe/Madrid localtime

# Apache configuration for DEV environment
COPY files/000-default.conf /etc/apache2/sites-available/
# Composer
COPY files/composer /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

# User for DEV environment
# Change for the user of virtual machine
RUN useradd nico -m
# RUN useradd vmamp -m

# This really helps for running the Symfony console with a docker exec
WORKDIR /var/www