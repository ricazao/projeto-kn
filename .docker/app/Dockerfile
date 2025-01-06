FROM php:8.2-apache

# Set document root to public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Allow rewrite
RUN a2enmod rewrite

# Install Git
RUN apt update && apt install -y git
RUN docker-php-ext-install pdo pdo_mysql

# Install Docker PHP Extension Installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Install PHP Extensions
RUN install-php-extensions gd intl pcntl redis zip bcmath intl ldap @composer

COPY .docker/app/php.ini /usr/local/etc/php/conf.d/99-overrides.ini
COPY .docker/app/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh
ENTRYPOINT ["/usr/local/bin/start.sh"]
