FROM php:5.6-apache

# Enable PHP extensions
RUN docker-php-ext-install mysqli

# Apache and PHP configuration
RUN a2enmod rewrite
ADD docker/apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
ADD docker/php/php.ini /usr/local/etc/php/php.ini

# Install dependencies
RUN apt-get update && \
    apt-get install zip unzip

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

# Setup /var/www as work directory and add all code to it
WORKDIR /var/www
COPY . /var/www

# Install composer dependencies
RUN composer install -o
