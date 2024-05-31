# Use the official PHP 8.2 image from Docker Hub
FROM php:8.2-cli

# Install Xdebug extension
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Configure Xdebug
RUN echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Expose port 9000 for xdebug
EXPOSE 9000

# Install git, unzip (required by Composer) and Composer itself
RUN apt-get update \
    && apt-get install -y \
    git \
    unzip \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www/html

COPY . /var/www/html

CMD ["tail", "-f", "/dev/null"]