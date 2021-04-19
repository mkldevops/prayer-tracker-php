FROM php:8.0-apache

RUN usermod -u 48 www-data && groupmod -g 48 www-data
RUN mkdir -p -m 777 /opt/apache/sessiontmp5/

EXPOSE 80
WORKDIR /var/www/html/

RUN apt update && apt install -y zip curl git cron libzip-dev vim mycli libicu-dev
RUN apt install -y zsh
RUN sh -c "$(curl -fsSL https://raw.github.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install -j$(nproc) opcache pdo_mysql zip
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/entrypoint.sh /opt/entrypoint.sh

COPY ./ /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install -n -q

COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/apache.conf /etc/apache2/conf-available/z-app.conf

RUN a2enmod rewrite remoteip && \
    a2enconf z-app

COPY docker/app-cron /etc/cron.d/app-cron
RUN chmod 0644 /etc/cron.d/app-cron
RUN crontab /etc/cron.d/app-cron

RUN touch /var/log/cron.log

#ENTRYPOINT ['/opt/entrypoint.sh']