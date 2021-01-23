FROM php:8.0-fpm-alpine

# [Dependencies]
RUN apk add libtool make automake autoconf nasm g++ git zip yarn optipng pkgconfig build-base zlib-dev

# [PHP]
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/php.ini"
#RUN echo "opcache.preload=/var/www/html/preload.php" >> "$PHP_INI_DIR/php.ini"

# [App]
COPY --chown=www-data:www-data . /var/www/html

# [Composer]
COPY --from=composer /usr/bin/composer /usr/bin/composer
USER www-data
RUN rm -rf vendor; \
    composer install -qno --no-dev

# [Node]
RUN rm -rf node_modules; \
    yarn install --silent; \
    yarn run prod --silent; \
    rm -rf node_modules

#CMD ["php-fpm"]
