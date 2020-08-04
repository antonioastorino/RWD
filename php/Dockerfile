FROM php:7.3.20-apache
RUN apt update && apt upgrade -y
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite
RUN a2enmod vhost_alias
RUN a2enmod status
RUN service apache2 restart
EXPOSE 80