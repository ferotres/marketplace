###############
# Application #
###############
FROM ubuntu:18.04
MAINTAINER @NautalEng

## Set environment vars
ENV PHP_VERSION 7.2
ENV PHP_INI_DIR /etc/php/${PHP_VERSION}/fpm/

ENV CONF_PATH docker/apache
## Install general packages
RUN apt-get update && \
    apt-get -y upgrade && \
    apt-get -y install \
		curl \
		locales \
        software-properties-common \
        apache2 \
        libcurl4-openssl-dev

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install -y nodejs
RUN npm install -g yarn
RUN locale-gen en_US.UTF-8

ENV TIMEZONE Europe/Madrid
ENV HOME /root
ENV DEBIAN_FRONTEND noninteractive
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

## Install PHP
RUN LANG=C.UTF-8 add-apt-repository -y ppa:ondrej/php \
    && apt-get update -y

RUN apt-get install -y \
    php${PHP_VERSION} \
    libapache2-mod-php${PHP_VERSION} \
    php${PHP_VERSION}-dev \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-soap \
    php-xdebug


## Set working directory
WORKDIR /var/www/app

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock /var/www/app/
RUN composer install  \
    --ignore-platform-reqs \
    --no-ansi \
    --no-autoloader \
    --no-interaction \
    --no-scripts

# We need to copy our whole application so that we can generate the autoload file inside the vendor folder.
COPY . /var/www/app
RUN composer dump-autoload --optimize --classmap-authoritative

# Clean apt repositories
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

## Copy config files

RUN rm -f /etc/apache2/sites-enabled/000-default.conf
COPY ${CONF_PATH}/php.ini /etc/php/${PHP_VERSION}/apache2/

## Enable Apache modules
RUN a2enmod rewrite
RUN a2enmod http2
RUN a2enmod headers
RUN a2enmod deflate
RUN a2enmod status

COPY ${CONF_PATH}/mods-enabled /etc/apache2/mods-enabled
COPY ${CONF_PATH}/httpd-vhosts.conf /etc/apache2/sites-enabled/

RUN echo ". /etc/environment" >> /etc/apache2/envvars

EXPOSE 80

COPY ${CONF_PATH}/docker-php-entrypoint /usr/local/bin
ENTRYPOINT ["docker-php-entrypoint"]

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
