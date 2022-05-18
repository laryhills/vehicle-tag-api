#FROM php:7.4-fpm
#
#COPY composer.lock  composer.json /var/www/html/
#
#
#WORKDIR /var/www/html
#
## Install Dependencies
#RUN apt-get update && apt-get install -y \
#    build-essential \
#    libpng-dev \
#    libzip-dev \
#    libjpeg62-turbo-dev \
#    libfreetype6-dev \
#    locales \
#    zip \
#    unzip \
#    curl \
#    git \
#    jpegoptim optipng  pngquant gifsicle \
#    vim \
#    nano
#  # php7.3-fpm php7.3-mysql php7.3-curl php7.3-gd php7.3-mbstring php7.3-xml php7.3-zip php7.3-bcmath php7.3-intl php7.3-json php7.3-opcache php7.3-readline php7.3-soap php7.3-xmlrpc php7.3-zip
#
## Clear cache
#RUN apt-get clean && rm -rf /var/lib/apt/lists/*
#
## Install extensions
#RUN docker-php-ext-install pdo_mysql zip exif pcntl
## RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
#RUN docker-php-ext-configure gd --with-freetype --with-jpeg
#RUN docker-php-ext-install gd
#
##Install Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
##Add USer for LAravel
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www
#
## Copy Application folder
#COPY . /var/www/html
#
## Copy existing permissions from folder to docker
#COPY --chown=www:www . /var/www/html
#RUN chown -R www-data:www-data /var/www/html
#
## change ccureent user to www
#USER www
#
## Expose port 9000 and start php-fpm server
#EXPOSE 9000
#CMD ["php-fpm"]

FROM richarvey/nginx-php-fpm:2.0.0

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
