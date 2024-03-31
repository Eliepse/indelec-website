FROM dunglas/frankenphp:1.1-php8.2-alpine as common

ARG USER=www-data

VOLUME /app/storage

RUN install-php-extensions opcache

# ==============
# Prod
# ==============

FROM common as prod

COPY . /app

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    mv ./docker/php/99-custom-prod.ini "$PHP_INI_DIR/conf.d/"; \
    adduser -D ${USER}; \
    # Ajouter la capacité supplémentaire de se lier aux ports 80 et 443
    setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
    # Donner l'accès en écriture à /data/caddy et /config/caddy
    chown -R ${USER}:${USER} /data/caddy; \
    chown -R ${USER}:${USER} /config/caddy; \
    chown -R ${USER}:${USER} /app; \
    chmod -R 766 /app;

USER ${USER}

RUN mkdir /app/storage; \
    mkdir /app/storage/logs; \
    mkdir /app/storage/framework;

# ==============
# Dev
# ==============

FROM common as dev

RUN install-php-extensions xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
    mv ./docker/php/99-custom-prod.ini "$PHP_INI_DIR/conf.d/"; \