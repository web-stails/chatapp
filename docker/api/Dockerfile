FROM php:8.3-fpm as base

ENV TZ="Europe/Moscow"

RUN apt-get update  \
    && apt-get install -y  \
    libmcrypt-dev  \
    vim \
    unzip \
    git \
    curl \
    nano \
    cron \
    zip \
    libicu-dev \
    mariadb-client \
    libmagickwand-dev \
    zlib1g-dev \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    g++ \
    libxml2-dev \
    libz-dev \
    default-jre \ 
    libreoffice-java-common \
    libreoffice \
    fontconfig \
    wget \
    --no-install-recommends \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка fonts

#RUN wget https://github.com/google/fonts/archive/main.tar.gz -O gf.tar.gz && tar -xf gf.tar.gz

#RUN mkdir -p /usr/share/fonts/truetype/google-fonts && \
#    find $PWD/fonts-main/ -name "*.ttf" -exec install -m644 {} /usr/share/fonts/truetype/google-fonts/ \; || return 1 && \
#    rm -rf gf.tar.gz fonts-main

RUN pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql zip exif pcntl soap intl gd \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install calendar

RUN usermod --shell /bin/bash www-data

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

WORKDIR /var/www

FROM base as dev

# установка пакетов необходимых только для разработки
RUN apt-get update  \
    && apt-get install -y nano \
    --no-install-recommends \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

FROM base as production

# Копирование и настройка файлов
COPY ./docker/api/conf/php.ini /usr/local/etc/php/conf.d/user.ini
COPY ./docker/api/conf/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./app/api /var/www
COPY ./app/api/.env /var/www/.env

RUN composer install --no-dev

RUN chown -R www-data:www-data /var/www/ \
    && chmod -R o+rwx /var/www/storage

# Настройка CRON
COPY ./docker/api/cron/crontab /tmp/crontab
RUN cat /tmp/crontab | crontab -u root -

