FROM php:7.4-fpm-buster
WORKDIR /app

RUN apt-get update && apt-get install -y libicu-dev git zip unzip libzip-dev \
                      && docker-php-ext-configure intl \
                      && docker-php-ext-install intl \
                      && docker-php-ext-configure zip \
                      && docker-php-ext-install zip

RUN docker-php-ext-install opcache
COPY php.ini /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# RUN pecl install xdebug-2.9.3 && docker-php-ext-enable xdebug
# Install xdebug
RUN pecl install xdebug-2.9.1 \
    && docker-php-ext-enable xdebug && \
       echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
       echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
       echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
       echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
       echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
       echo "xdebug.remote_host=172.17.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
       echo "xdebug.remote_log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
