FROM php:7.4-fpm

# install pdo
RUN docker-php-ext-install pdo_mysql

# install composer
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

#install git
RUN apt-get update \
    && apt-get install -y git

#install nodejs
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - \ 
    && apt-get install -y nodejs