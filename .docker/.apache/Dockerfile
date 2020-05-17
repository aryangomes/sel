FROM php:7.2-apache

# replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# update and upgrades packages
RUN apt-get update && apt-get upgrade -y


# development packages
RUN apt-get install -y \
   iputils-ping \
   apt-utils \
   wget \
   git \
   nano \
   zip \
   curl \
   sudo \
   unzip \
   build-essential \
   ca-certificates \
   libicu-dev \
   libbz2-dev \
   libpng-dev \
   libjpeg-dev \
   libmcrypt-dev \
   libreadline-dev \
   libfreetype6-dev \
   g++

# mod_rewrite for URL rewrite and mod_headers for .htaccess extra headers like Access-Control-Allow-Origin-
RUN a2enmod rewrite headers

# start with base php config, then add extensions
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN docker-php-ext-install \
   bz2 \
   intl \
   iconv \
   bcmath \
   opcache \
   calendar \
   mbstring \
   pdo_mysql \
   zip
   

# Instalação e configuração do XDebug
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >>  /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_connect_back=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=docker" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.default_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# nvm
ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 12.16.2

# Install nvm with node and npm
RUN curl https://raw.githubusercontent.com/creationix/nvm/v0.30.1/install.sh | bash \
   && source $NVM_DIR/nvm.sh \
   && nvm install $NODE_VERSION \
   && nvm alias default $NODE_VERSION \
   && nvm use default 

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH      $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH




# ports
EXPOSE 80 443


#REFS
# https://dev.to/veevidify/docker-compose-up-your-entire-laravel-apache-mysql-development-environment-45ea
# https://github.com/t3kit/php7.2-apache/blob/master/Dockerfile
# https://medium.com/@alexcvcoelho/ambiente-de-desenvolvimento-php-com-docker-xdebug-vscode-94bda2b49be9