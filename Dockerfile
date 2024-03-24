FROM mkldevops/php-fpm-alpine:8.3 as base

ENV APP_ENV=prod

COPY --link docker/app.ini $PHP_INI_DIR/conf.d/

EXPOSE 80
CMD ["symfony", "serve", "--no-tls", "--allow-http", "--port=80"]

FROM base as prod

COPY --link . .
RUN set -eux; \
	symfony composer install --no-cache --prefer-dist --no-scripts --no-progress

FROM base as dev

ENV APP_ENV=dev
