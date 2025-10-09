FROM wordpress:6.5-php8.3-apache

COPY --chown=www-data:www-data . /var/www/html
