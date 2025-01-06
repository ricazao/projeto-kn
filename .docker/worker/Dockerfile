FROM php:8.2-cli

# Install Git
RUN apt update && apt install -y git
RUN docker-php-ext-install pdo pdo_mysql

# Install Docker PHP Extension Installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Install PHP Extensions
RUN install-php-extensions gd intl pcntl redis zip bcmath intl ldap @composer

COPY .docker/worker/php.ini /usr/local/etc/php/conf.d/99-overrides.ini
