ARG PHP_VERSION

# Using PHP version from env defaulting to 7.4
FROM php:${PHP_VERSION:-7.4}-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN echo "Installing PHP version ${PHP_VERSION}"

# Install dependencies
RUN apt-get update && apt-get install -y \
    git wget zip unzip

# Install additional packages
RUN apt-get update && apt-get install --no-install-recommends -y \
    libmagickwand-dev

# Clean up apt-get lists
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install and enable imagick
RUN pecl install imagick && docker-php-ext-enable imagick

# Install and enable xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install and enable mysqli and pdo
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable mysqli pdo pdo_mysql

# Set the workdir
WORKDIR /var/www/html

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Download composer.phar
RUN curl -L -o /var/www/html/composer.phar -sS https://getcomposer.org/download/latest-stable/composer.phar

# Could copy .env files here and use it as a template here

EXPOSE 9000

# Copy and fire up entrypoint
COPY ./server/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod 777 /usr/local/bin/docker-entrypoint.sh \
    && chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Run php-fpm in foreground
CMD ["php-fpm","-F"]
