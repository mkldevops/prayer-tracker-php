FROM dunglas/frankenphp:alpine-builder AS builder

RUN apk add --no-cache bash zsh git shadow

RUN install-php-extensions \
    intl \
    apcu

# Symfony cli
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | sh
RUN apk add symfony-cli && \
  sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"

#ENV SERVER_NAME=:80
ENV APP_ENV=prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /app
COPY . /app

RUN symfony composer install --no-dev --no-interaction --no-progress --no-scripts