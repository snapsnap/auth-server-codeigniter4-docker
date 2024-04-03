FROM php:8.2.10-apache

# ENV ACCEPT_EULA=Y

RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y libicu-dev libodbc1 odbcinst odbcinst1debian2 \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && apt-get install -y gnupg2

RUN apt-get install -y curl apt-transport-https
RUN apt-get update
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - 
RUN curl https://packages.microsoft.com/config/debian/10/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev 
RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv pdo_sqlsrv

RUN a2enmod rewrite
RUN service apache2 restart

COPY ./src /var/www/html/
COPY ./config/php.ini /usr/local/etc/php/
# COPY ./config/ci4.conf /etc/apache2/sites-enabled/ci4.conf

# RUN apt-get update && apt-get upgrade -y