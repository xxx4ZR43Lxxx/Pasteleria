FROM php:8.1-apache

# Instala extensiones necesarias
RUN docker-php-ext-install mysqli

# Configura el directorio de trabajo
WORKDIR /var/www/html

# Asegura que los directorios `uploads` y `postres` existan con los permisos correctos
RUN mkdir -p /var/www/html/img/uploads /var/www/html/img/postres && \
    chown www-data:www-data /var/www/html/img/uploads /var/www/html/img/postres && \
    chmod 755 /var/www/html/img/uploads /var/www/html/img/postres


# Configura permisos para Apache
#RUN chown -R www-data:www-data /var/www/html