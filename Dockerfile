FROM wordpress:6.5-php8.3-apache

COPY php-custom.ini /usr/local/etc/php/conf.d/99-custom.ini
COPY --chown=www-data:www-data . /var/www/html
RUN rm /var/www/html/php-custom.ini
