FROM php:7.2-apache

#COPY ../../src /var/www/html/
#COPY ./docker-php.conf /etc/apache2/conf-enabled/docker-php.conf
#COPY /etc/apache2/conf-enabled /etc/apache2/conf-enabled
#COPY /etc/apache2/mods-enabled /etc/apache2/mods-enabled

RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite

#CMD ["php"]

#EXPOSE 80 443
