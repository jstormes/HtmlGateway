# This is a convenience container to run php/bash with tools
FROM php:7.1-cli
MAINTAINER You

# Install Linux tools
RUN apt-get update \
 && apt-get install -y net-tools curl wget git zip unzip mysql-client joe

RUN docker-php-ext-install pdo pdo_mysql

# Install Composer tools
RUN wget https://getcomposer.org/installer \
 && php installer \
 && mv composer.phar /usr/local/bin/composer \
 && composer global require phpunit/phpunit \
   phpunit/dbunit \
   phing/phing \
   sebastian/phpcpd \
   phploc/phploc \
   phpmd/phpmd \
   squizlabs/php_codesniffer

# Install PHP XDebug, default should work in most situation.
# See also XDEBUG_CONFIG in docker-compose.yml.
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Add our script files so they can be found
ENV PATH /opt/project/vendor/bin:/opt/project/docker/bin:$PATH