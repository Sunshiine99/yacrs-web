FROM php:5.6-apache

# Enable PHP extensions
RUN docker-php-ext-install mysqli
 # Install needed php extensions: ldap
RUN \
    apt-get update && \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

RUN \
    apt-get update && \
    apt-get install ruby-full -y && \
    gem install sass --no-user-install

# Apache and PHP configuration
RUN a2enmod rewrite
ADD docker/apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
ADD docker/php/php.ini /usr/local/etc/php/php.ini

# Install dependencies
RUN apt-get update && \
    apt-get install zip unzip wget -y

# Install composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

# Install PhpUnit globally
#curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit-5.7.9.phar
#chmod +x /usr/local/bin/phpunit
RUN wget https://phar.phpunit.de/phpunit-5.7.9.phar -O phpunit.phar && \
    chmod +x phpunit.phar && \
    mv phpunit.phar /usr/local/bin/phpunit

# Setup /var/www as work directory and add all code to it
WORKDIR /var/www
COPY . /var/www

# Install composer dependencies
RUN composer install -o
