FROM php:8.2-fpm

RUN apt-get update
RUN apt-get install -y libpq-dev libpng-dev curl unzip zip git jq supervisor postgresql-client
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql && docker-php-ext-enable pdo_pgsql 
RUN apt-get install -y libxrender1
RUN apt-get install -y libfontconfig1 libxml2-dev && docker-php-ext-install soap && docker-php-ext-enable soap
RUN apt-get install -y \
    libzip-dev

#install redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis


# install composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy necessary files and change permissions
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# install php dependencies
RUN composer install --no-dev --prefer-dist 

RUN chown -R www-data:www-data /var/www/html/vendor \
    && chmod -R 775 /var/www/html/vendor


ADD docker/app/mainsupervisor.conf /etc/supervisor/conf.d/mainsupervisor.conf
COPY docker/app/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY docker/app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/fpm.conf /usr/local/etc/php-fpm.d/www.conf


WORKDIR /var/www/html

VOLUME ["/var/www/html/storage/app"]


EXPOSE 9000